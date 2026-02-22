<?php

namespace App\Http\Controllers;

use App\Models\BankingTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BankingController extends Controller
{
    /**
     * Display banking overview
     */
    public function index(Request $request)
    {
        $query = BankingTransaction::query()
            ->orderBy('buchungstag', 'desc')
            ->orderBy('id', 'desc');

        if ($request->has('kategorie') && $request->kategorie) {
            $query->where('kategorie', $request->kategorie);
        }

        if ($request->has('von') && $request->von) {
            $query->where('buchungstag', '>=', $request->von);
        }
        if ($request->has('bis') && $request->bis) {
            $query->where('buchungstag', '<=', $request->bis);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('verwendungszweck', 'like', "%{$search}%")
                    ->orWhere('beguenstigter', 'like', "%{$search}%")
                    ->orWhere('buchungstext', 'like', "%{$search}%");
            });
        }

        $transactions = $query->paginate(50);
        
        $categories = BankingTransaction::distinct()
            ->whereNotNull('kategorie')
            ->orderBy('kategorie')
            ->pluck('kategorie');

        $stats = [
            'total_income' => (clone $query)->where('betrag', '>', 0)->sum('betrag') ?: 0,
            'total_expense' => (clone $query)->where('betrag', '<', 0)->sum('betrag') ?: 0,
            'count' => (clone $query)->count(),
        ];

        return view('banking.index', compact('transactions', 'categories', 'stats'));
    }

    public function upload()
    {
        return view('banking.upload');
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        $file = $request->file('csv_file');
        $csvString = file_get_contents($file->getRealPath());

        // Simple CSV parsing: split by newline
        $lines = explode("\n", trim($csvString));
        
        if (empty($lines)) {
            return back()->with('error', 'Keine Daten gefunden.');
        }

        // Parse header
        $header = str_getcsv(array_shift($lines), ';', '"');
        
        // Map header columns
        $headerMap = [];
        foreach ($header as $index => $column) {
            $column = trim($column);
            $headerMap[$index] = $this->mapColumn($column);
        }

        $imported = 0;
        $updated = 0;
        $skipped = 0;
        $errors = [];

        // Parse each data line
        foreach ($lines as $lineNum => $line) {
            $line = trim($line);
            if (empty($line)) continue;

            try {
                $row = str_getcsv($line, ';', '"');
                
                if (count($row) < 12) {
                    $skipped++;
                    continue;
                }

                // Combine header with row data
                $data = [];
                foreach ($row as $index => $value) {
                    $fieldName = $headerMap[$index] ?? null;
                    if ($fieldName) {
                        $data[$fieldName] = trim($value);
                    }
                }

                // Skip if no essential data
                if (empty($data['buchungstag']) && empty($data['betrag'])) {
                    $skipped++;
                    continue;
                }

                // Generate hash for this transaction
                $hash = BankingTransaction::generateHash($data);

                // Parse dates
                $data['buchungstag'] = BankingTransaction::parseDate($data['buchungstag'] ?? '');
                $data['valutadatum'] = BankingTransaction::parseDate($data['valutadatum'] ?? '');

                // Parse amount
                $data['betrag'] = BankingTransaction::parseAmount($data['betrag'] ?? '');

                // Check if exists - update or insert
                $exists = BankingTransaction::find($hash);
                
                if ($exists) {
                    $exists->update($data);
                    $updated++;
                } else {
                    $data['id'] = $hash;
                    BankingTransaction::create($data);
                    $imported++;
                }

            } catch (\Exception $e) {
                $errors[] = "Line " . ($lineNum + 1) . ": " . $e->getMessage();
                Log::error('Banking import error: ' . $e->getMessage());
            }
        }

        $message = "Import abgeschlossen: {$imported} neue, {$updated} aktualisiert.";
        if ($skipped > 0) {
            $message .= " ({$skipped} uebersprungen)";
        }
        if (count($errors) > 0) {
            $message .= ' ' . count($errors) . ' Fehler.';
        }

        return redirect()->route('banking.index')->with('success', $message);
    }

    private function mapColumn(string $column): ?string
    {
        $mapping = [
            'Auftragskonto' => 'auftragskonto',
            'Buchungstag' => 'buchungstag',
            'Valutadatum' => 'valutadatum',
            'Buchungstext' => 'buchungstext',
            'Verwendungszweck' => 'verwendungszweck',
            'Beguenstigter/Zahlungspflichtiger' => 'beguenstigter',
            'Kontonummer/IBAN' => 'iban',
            'BIC (SWIFT-Code)' => 'bic',
            'Betrag' => 'betrag',
            'Waehrung' => 'waehrung',
            'Info' => 'info',
            'Kategorie' => 'kategorie',
        ];

        return $mapping[$column] ?? null;
    }
}

<?php

namespace Tests\Feature;

use Tests\TestCase;

class PmsRoutesTest extends TestCase
{
    /** Test dass alle Hauptseiten ohne 500-Fehler laden */
    public function test_routes_are_accessible(): void
    {
        $routes = [
            "/rooms",
            "/guests", 
            "/articles",
            "/reservations",
            "/room-types",
            "/prices",
            "/firms",
            "/settings",
        ];
        
        foreach ($routes as $route) {
            $response = $this->get($route);
            $response->assertStatus(200);
            
            // Prüfe auf Laravel-Fehler im Response
            $content = $response->getContent();
            $this->assertFalse(
                str_contains($content, 'SQLSTATE'),
                "SQL-Fehler in Response für Route: $route"
            );
            $this->assertFalse(
                str_contains($content, 'Unknown column'),
                "Unknown column Fehler in Response für Route: $route"
            );
            $this->assertFalse(
                str_contains($content, 'Column not found'),
                "Column not found Fehler in Response für Route: $route"
            );
        }
    }
    
    /** Test dass Sidebar korrekt geladen wird */
    public function test_sidebar_is_included(): void
    {
        $pages = ["/rooms", "/guests", "/articles", "/prices", "/room-types", "/firms", "/settings"];
        
        foreach ($pages as $page) {
            $response = $this->get($page);
            $response->assertSee("Abmelden");
            $response->assertSee("Stammdaten");
        }
    }
    
    /** Test Dropdown-JavaScript funktioniert */
    public function test_dropdown_script_present(): void
    {
        $response = $this->get("/rooms");
        $response->assertSee("nav-dropdown");
        $response->assertSee("toggleDropdown");
    }
}

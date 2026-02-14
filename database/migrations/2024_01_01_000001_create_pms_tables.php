<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create("organizations", function (Blueprint $t) {
            $t->id();$t->string("name");$t->string("slug")->unique();$t->text("address")->nullable();$t->string("email")->nullable();$t->string("phone")->nullable();$t->timestamps();
        });
        Schema::create("firms", function (Blueprint $t) {
            $t->id();$t->foreignId("organization_id")->constrained()->onDelete("cascade");$t->string("name");$t->string("code")->unique();$t->text("address")->nullable();$t->string("email")->nullable();$t->string("phone")->nullable();$t->string("tax_number")->nullable();$t->timestamps();
        });
        Schema::create("users", function (Blueprint $t) {
            $t->id();$t->foreignId("firm_id")->nullable()->constrained()->onDelete("set null");$t->string("username")->unique();$t->string("email")->unique();$t->timestamp("email_verified_at")->nullable();$t->string("password");$t->string("first_name")->nullable();$t->string("last_name")->nullable();$t->string("phone")->nullable();$t->integer("security_level")->default(1);$t->string("timezone")->default("Europe/Berlin");$t->string("language")->default("de");$t->text("dashboard_config")->nullable();$t->boolean("is_active")->default(true);$t->timestamp("last_login")->nullable();$t->timestamps();$t->softDeletes();
        });
        Schema::create("guests", function (Blueprint $t) {
            $t->id();$t->foreignId("firm_id")->nullable()->constrained()->onDelete("set null");$t->string("salutation")->nullable();$t->string("title")->nullable();$t->string("first_name");$t->string("last_name");$t->string("email")->nullable();$t->string("phone")->nullable();$t->text("address")->nullable();$t->date("birth_date")->nullable();$t->string("nationality")->nullable();$t->string("id_type")->nullable();$t->string("id_number")->nullable();$t->date("id_valid_until")->nullable();$t->boolean("is_blacklisted")->default(false);$t->text("blacklist_reason")->nullable();$t->integer("stay_count")->default(0);$t->date("last_stay")->nullable();$t->text("notes")->nullable();$t->timestamps();$t->softDeletes();
        });
        Schema::create("room_types", function (Blueprint $t) {
            $t->id();$t->foreignId("firm_id")->constrained()->onDelete("cascade");$t->string("name");$t->string("code")->unique();$t->text("description")->nullable();$t->integer("bed_count")->default(1);$t->integer("max_persons")->default(2);$t->decimal("size_sqm", 8, 2)->nullable();$t->timestamps();
        });
        Schema::create("rooms", function (Blueprint $t) {
            $t->id();$t->foreignId("firm_id")->constrained()->onDelete("cascade");$t->foreignId("room_type_id")->nullable()->constrained()->onDelete("set null");$t->string("room_number", 20);$t->string("floor")->nullable();$t->string("building")->nullable();$t->text("description")->nullable();$t->string("status")->default("available");$t->integer("sort_order")->default(0);$t->string("color")->nullable();$t->timestamps();
        });
        Schema::create("articles", function (Blueprint $t) {
            $t->id();$t->foreignId("firm_id")->constrained()->onDelete("cascade");$t->string("code", 50)->unique();$t->string("name");$t->text("description")->nullable();$t->string("category")->nullable();$t->decimal("price", 10, 2)->default(0);$t->string("price_type")->default("netto");$t->decimal("tax_rate", 5, 2)->default(19);$t->boolean("is_active")->default(true);$t->boolean("is_room_rate")->default(false);$t->boolean("is_meal")->default(false);$t->date("valid_from")->nullable();$t->date("valid_until")->nullable();$t->timestamps();
        });
        Schema::create("reservations", function (Blueprint $t) {
            $t->id();$t->foreignId("firm_id")->constrained()->onDelete("cascade");$t->foreignId("guest_id")->nullable()->constrained()->onDelete("set null");$t->foreignId("room_id")->nullable()->constrained()->onDelete("set null");$t->string("reservation_number")->unique();$t->date("arrival");$t->date("departure");$t->integer("adults")->default(1);$t->integer("children")->default(0);$t->string("status")->default("confirmed");$t->string("source")->nullable();$t->text("notes")->nullable();$t->text("internal_notes")->nullable();$t->decimal("total_amount", 10, 2)->default(0);$t->decimal("paid_amount", 10, 2)->default(0);$t->string("currency", 3)->default("EUR");$t->timestamps();$t->softDeletes();
        });
        Schema::create("reservation_participants", function (Blueprint $t) {
            $t->id();$t->foreignId("reservation_id")->constrained()->onDelete("cascade");$t->foreignId("guest_id")->nullable()->constrained()->onDelete("set null");$t->string("first_name")->nullable();$t->string("last_name")->nullable();$t->date("birth_date")->nullable();$t->timestamps();
        });
        Schema::create("reservation_articles", function (Blueprint $t) {
            $t->id();$t->foreignId("reservation_id")->constrained()->onDelete("cascade");$t->foreignId("article_id")->nullable()->constrained()->onDelete("set null");$t->string("article_name");$t->decimal("quantity", 10, 2)->default(1);$t->decimal("unit_price", 10, 2);$t->decimal("total_price", 10, 2);$t->date("service_date");$t->text("notes")->nullable();$t->timestamps();
        });
        Schema::create("journals", function (Blueprint $t) {
            $t->id();$t->foreignId("firm_id")->constrained()->onDelete("cascade");$t->foreignId("reservation_id")->nullable()->constrained()->onDelete("set null");$t->foreignId("guest_id")->nullable()->constrained()->onDelete("set null");$t->string("booking_number")->unique();$t->date("booking_date");$t->string("type");$t->string("sub_type")->nullable();$t->decimal("amount", 10, 2);$t->decimal("tax_amount", 10, 2)->default(0);$t->string("direction")->default("in");$t->string("payment_method")->nullable();$t->text("description")->nullable();$t->string("status")->default("posted");$t->timestamps();
        });
        Schema::create("invoices", function (Blueprint $t) {
            $t->id();$t->foreignId("firm_id")->constrained()->onDelete("cascade");$t->foreignId("reservation_id")->nullable()->constrained()->onDelete("set null");$t->foreignId("guest_id")->nullable()->constrained()->onDelete("set null");$t->string("invoice_number")->unique();$t->date("invoice_date");$t->date("due_date");$t->decimal("subtotal", 10, 2);$t->decimal("tax_amount", 10, 2);$t->decimal("total", 10, 2);$t->decimal("paid_amount", 10, 2)->default(0);$t->string("status")->default("draft");$t->text("notes")->nullable();$t->timestamps();
        });
        Schema::create("housekeeping", function (Blueprint $t) {
            $t->id();$t->foreignId("room_id")->constrained()->onDelete("cascade");$t->date("date");$t->string("status")->default("clean");$t->text("notes")->nullable();$t->string("cleaned_by")->nullable();$t->timestamps();
        });
        Schema::create("meals", function (Blueprint $t) {
            $t->id();$t->foreignId("firm_id")->constrained()->onDelete("cascade");$t->string("name");$t->decimal("price", 10, 2);$t->string("description")->nullable();$t->timestamps();
        });
        Schema::create("payments", function (Blueprint $t) {
            $t->id();$t->foreignId("firm_id")->constrained()->onDelete("cascade");$t->foreignId("reservation_id")->nullable()->constrained()->onDelete("set null");$t->decimal("amount", 10, 2);$t->string("method");$t->string("transaction_id")->nullable();$t->string("status")->default("completed");$t->text("notes")->nullable();$t->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists("payments");Schema::dropIfExists("meals");Schema::dropIfExists("housekeeping");Schema::dropIfExists("invoices");Schema::dropIfExists("journals");Schema::dropIfExists("reservation_articles");Schema::dropIfExists("reservation_participants");Schema::dropIfExists("reservations");Schema::dropIfExists("articles");Schema::dropIfExists("rooms");Schema::dropIfExists("room_types");Schema::dropIfExists("guests");Schema::dropIfExists("users");Schema::dropIfExists("firms");Schema::dropIfExists("organizations");
    }
};

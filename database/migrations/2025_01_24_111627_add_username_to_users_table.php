<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable()->after('name'); // Add the username column as nullable
        });

        // Populate the username column with unique values
        $users = \DB::table('users')->get();

        foreach ($users as $user) {
            $uniqueUsername = $user->email;

            // Ensure the username is unique
            while (\DB::table('users')->where('username', $uniqueUsername)->exists()) {
                $uniqueUsername = $user->email . '_' . uniqid(); // Append a unique suffix if necessary
            }

            // Update the user record with the unique username
            \DB::table('users')
                ->where('id', $user->id)
                ->update(['username' => $uniqueUsername]);
        }

        // Add the unique and non-nullable constraint
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('username');
        });
    }
};

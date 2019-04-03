<?php
use Illuminate\Support\Facades\Schema; use Illuminate\Database\Schema\Blueprint; use Illuminate\Database\Migrations\Migration; class CreateJobsTable extends Migration { public function up() { Schema::create('jobs', function (Blueprint $spd19505) { $spd19505->bigIncrements('id'); $spd19505->string('queue')->index(); $spd19505->longText('payload'); $spd19505->unsignedTinyInteger('attempts'); $spd19505->unsignedInteger('reserved_at')->nullable(); $spd19505->unsignedInteger('available_at'); $spd19505->unsignedInteger('created_at'); }); } public function down() { Schema::dropIfExists('jobs'); } }
<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UserSeeder::class);
        // $this->call(MasterTugasSeeder::class);
        // $this->call(KuisionerSeeder::class);
        // $this->call(UserSeeder::class);
        // $this->call(PertanyaanKuisionerSeeder::class);
        // $this->call(PilihanSeeder::class);
        $this->call(KategoriPertanyaanKuisionerSeeder::class);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $districts = [
            ['division_id' => 1, 'name' => 'Dhaka'],
            ['division_id' => 1, 'name' => 'Faridpur'], //2
            ['division_id' => 1, 'name' => 'Gazipur'], //3
            ['division_id' => 1, 'name' => 'Gopalganj'], //4
            ['division_id' => 1, 'name' => 'Kishoreganj'], //5
            ['division_id' => 1, 'name' => 'Madaripur'], //6
            ['division_id' => 1, 'name' => 'Manikganj'], //7
            ['division_id' => 1, 'name' => 'Munshiganj'], //8
            ['division_id' => 1, 'name' => 'Narayanganj'], //9
            ['division_id' => 1, 'name' => 'Narsingdi'], //10
            ['division_id' => 1, 'name' => 'Rajbari'], //11
            ['division_id' => 1, 'name' => 'Shariatpur'], //12
            ['division_id' => 1, 'name' => 'Tangail'], //13
            ['division_id' => 2, 'name' => 'Bandarban'], //14
            ['division_id' => 2, 'name' => 'Brahmanbaria'], //15
            ['division_id' => 2, 'name' => 'Chandpur'], //16
            ['division_id' => 2, 'name' => 'Chittagong'], //17
            ['division_id' => 2, 'name' => 'Comilla'], //18
            ['division_id' => 2, 'name' => 'Cox\'s Bazar'], //19
            ['division_id' => 2, 'name' => 'Feni'], //20
            ['division_id' => 2, 'name' => 'Khagrachari'], //21
            ['division_id' => 2, 'name' => 'Lakshmipur'], //22
            ['division_id' => 2, 'name' => 'Noakhali'], //23
            ['division_id' => 2, 'name' => 'Rangamati'], //24
            ['division_id' => 3, 'name' => 'Bagerhat'], //25
            ['division_id' => 3, 'name' => 'Chuadanga'], //26
            ['division_id' => 3, 'name' => 'Jessore'], //27
            ['division_id' => 3, 'name' => 'Jhenaidah'], //28
            ['division_id' => 3, 'name' => 'Khulna'], //29
            ['division_id' => 3, 'name' => 'Kushtia'], //30
            ['division_id' => 3, 'name' => 'Magura'], //31
            ['division_id' => 3, 'name' => 'Meherpur'], //32
            ['division_id' => 3, 'name' => 'Narail'], //33
            ['division_id' => 3, 'name' => 'Satkhira'], //34
            ['division_id' => 4, 'name' => 'Bogra'], //35
            ['division_id' => 4, 'name' => 'Joypurhat'], //36
            ['division_id' => 4, 'name' => 'Naogaon'], //37
            ['division_id' => 4, 'name' => 'Natore'], //38
            ['division_id' => 4, 'name' => 'Nawabganj'], //39
            ['division_id' => 4, 'name' => 'Pabna'], //40
            ['division_id' => 4, 'name' => 'Rajshahi'], //41
            ['division_id' => 4, 'name' => 'Sirajganj'], //42
            ['division_id' => 5, 'name' => 'Dinajpur'], //43
            ['division_id' => 5, 'name' => 'Gaibandha'], //44
            ['division_id' => 5, 'name' => 'Kurigram'], //45
            ['division_id' => 5, 'name' => 'Lalmonirhat'], //46
            ['division_id' => 5, 'name' => 'Nilphamari'], //47
            ['division_id' => 5, 'name' => 'Panchagarh'], //48
            ['division_id' => 5, 'name' => 'Rangpur'], //49
            ['division_id' => 5, 'name' => 'Thakurgaon'], //50
            ['division_id' => 6, 'name' => 'Habiganj'], //51
            ['division_id' => 6, 'name' => 'Moulvibazar'], //52
            ['division_id' => 6, 'name' => 'Sunamganj'], //53
            ['division_id' => 6, 'name' => 'Sylhet'], //54
            ['division_id' => 7, 'name' => 'Barguna'], //55
            ['division_id' => 7, 'name' => 'Barisal'], //56
            ['division_id' => 7, 'name' => 'Bhola'], //57
            ['division_id' => 7, 'name' => 'Jhalokati'], //58
            ['division_id' => 7, 'name' => 'Patuakhali'], //59
            ['division_id' => 7, 'name' => 'Pirojpur'], //60
            ['division_id' => 8, 'name' => 'Jamalpur'], //61
            ['division_id' => 8, 'name' => 'Mymensingh'], //62
            ['division_id' => 8, 'name' => 'Netrokona'], //63
            ['division_id' => 8, 'name' => 'Sherpur'], //64

        ];

        DB::table('districts')->insert($districts);
    }
}

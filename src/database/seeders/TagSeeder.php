<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Tag;
class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
                        $tags = [
    '演習あり' => ['CS101', 'PG220', 'DS230', 'PG120', 'UX250', 'BZ120'],
    '少人数' => ['EN101', 'EN102', 'CN100', 'UX250', 'SE300'],
    '英語で実施' => ['EN101', 'EN102'],
    'レポート評価' => ['IE110', 'SO120', 'HI130', 'LI140', 'CA200', 'ART100'],
    '1年生向け' => ['CS101', 'EC100', 'IE110', 'EN101', 'PS100', 'LW110'],
];
    foreach($tags as $name=>$codes){
        $tag=Tag::updateOrCreate(
            ['name'=>$name]
        );

        foreach($codes as $code){
            $course=Course::where('code',$code)->first();

            if($course !== null){

            $exists=$course->tags()
            ->where('tags.id',$tag->id)
            ->exists();

            if (!$exists){
                $course->tags()->attach($tag->id);
            }
            }


        }
        
    }
    }
}

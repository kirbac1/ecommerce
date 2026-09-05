<?php

namespace Database\Seeders;

use App\Category;
use App\Models\Model;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    public function run()
    {
        Model::unguard();
        $categories = [
            ['name' => 'Kontti', 'deletable' => false, 'children' => [
                ['name' => 'Cereals', 'children' => [
                    ['name' => 'Aksiyon'],
                    ['name' => 'Burcu'],
                    ['name' => 'Couscous/Flour'],
                    ['name' => 'Duru'],
                    ['name' => 'Gülcan cereals'],
                    ['name' => 'Mis'],
                ]],
                ['name' => 'Akar'],
                ['name' => 'Arabian Speciality', 'children' => [
                    ['name' => 'Chtourra'],
                    ['name' => 'Durra'],
                ]],
                ['name' => 'Building Materials', 'children' => [
                    ['name' => 'Flooring']
                ]],
                ['name' => 'Canned Food', 'children' => [
                    ['name' => 'Canned Food'],
                    ['name' => 'Canned Legumes'],
                    ['name' => 'Pickles'],
                    ['name' => 'Sauces'],
                    ['name' => 'Tomato and Paprika'],
                ]],
                ['name' => 'Catering', 'children' => [
                    ['name' => 'Packages'],
                ]],
                ['name' => 'Cheese', 'children' => [
                    ['name' => 'Cheese'],
                    ['name' => 'Gazi Cheese'],
                    ['name' => 'Gülcan Cheese'],
                    ['name' => 'Nefis'],
                    ['name' => 'Pinar'],
                    ['name' => 'Slices and block cheese'],
                    ['name' => 'Yayla cheese'],
                ]],
                ['name' => 'Confectionery', 'children' => [
                    ['name' => 'Ani'],
                    ['name' => 'Bonbon'],
                    ['name' => 'Cookies'],
                    ['name' => 'Dessert'],
                    ['name' => 'Elvan'],
                    ['name' => 'Eurokrem'],
                    ['name' => 'Gum'],
                    ['name' => 'Haribo'],
                    ['name' => 'Pastries'],
                    ['name' => 'Various Confectionery'],
                    ['name' => 'Waffles'],
                ]],
                ['name' => 'Cosmetic and Cleaning Supplies', 'children' => [
                    ['name' => 'ACE'],
                    ['name' => 'Cleanser'],
                    ['name' => 'Haci sakir'],
                    ['name' => 'Laundry detergent and cosmetics'],
                    ['name' => 'Levent cöz'],
                    ['name' => 'Soaps and shampoos'],
                    ['name' => 'Tex'],
                ]],
                ['name' => 'Delicatessen, Halvak, Turkish delight', 'children' => [
                    ['name' => 'Delicatessen'],
                    ['name' => 'Halvah'],
                    ['name' => 'Turkish Delight'],
                ]],
                ['name' => 'Drinks', 'children' => [
                    ['name' => 'Capri sonne'],
                    ['name' => 'Drink Powders'],
                    ['name' => 'Fruit'],
                    ['name' => 'Meysu'],
                    ['name' => 'Uludag'],
                    ['name' => 'Various Drinks'],
                ]],
                ['name' => 'Dry Fruits', 'children' => [
                    ['name' => 'Finnish'],
                    ['name' => 'test 2'],
                    ['name' => 'Organic'],
                ]],
                ['name' => 'Fish and Meat', 'children' => [
                    ['name' => 'Egeturk/efepasa'],
                    ['name' => 'Fish'],
                    ['name' => 'Fresh Product'],
                    ['name' => 'Frozen Foods'],
                    ['name' => 'Gülcan sausages, wurst'],
                    ['name' => 'Kelmendi'],
                    ['name' => 'Robert'],
                    ['name' => 'Russian wurst'],
                    ['name' => 'Slice, salami, wurst'],
                    ['name' => 'Sölen'],
                    ['name' => 'Sucuk'],
                    ['name' => 'Sütdıyarı'],
                    ['name' => 'Wurst and fish'],
                    ['name' => 'Yayla'],
                ]],
                ['name' => 'For baking', 'children' => [
                    ['name' => 'Basak'],
                    ['name' => 'Dr. Oetker'],
                    ['name' => 'For Desserts'],
                ]],
                ['name' => 'Frozen', 'children' => [
                    ['name' => 'Saadet'],
                    ['name' => 'Samaa Products'],
                ]],
                ['name' => 'Fruits', 'children' => [
                    ['name' => 'Apples'],
                    ['name' => 'Bananas'],
                    ['name' => 'Citrus Fruits'],
                    ['name' => 'Fruits'],
                    ['name' => 'Grapes'],
                    ['name' => 'Melons'],
                    ['name' => 'Nectarines and peaches'],
                ]],
                ['name' => 'Household', 'children' => [
                    ['name' => 'Household'],
                ]],
                ['name' => 'Legumes', 'children' => [
                    ['name' => 'Beans'],
                    ['name' => 'Chickpeas'],
                    ['name' => 'Dried legumes'],
                    ['name' => 'Legumes'],
                ]],
                ['name' => 'Meray'],
                ['name' => 'Nuts', 'children' => [
                    ['name' => 'Nuts and seeds'],
                ]],
                ['name' => 'Oil, vinegar and salgam', 'children' => [
                    ['name' => 'Aribella Oils and Vinegars'],
                    ['name' => 'Gülcan Oils and Vinegars'],
                    ['name' => 'Krystal Oils'],
                    ['name' => 'Lorena Oils'],
                    ['name' => 'Oils'],
                    ['name' => 'Various Oils and Vinegars'],
                    ['name' => 'Yonca Oils'],
                ]],
                ['name' => 'Olives', 'children' => [
                    ['name' => 'Durmaz'],
                    ['name' => 'Greek Olives'],
                    ['name' => 'Gülcan Olives'],
                    ['name' => 'Marmara birlik'],
                    ['name' => 'Morenita'],
                    ['name' => 'Olives'],
                ]],
                ['name' => 'Rice', 'children' => [
                    ['name' => 'Rice'],
                ]],
                ['name' => 'Russian Specialties', 'children' => [
                    ['name' => 'Ryssuab Specialties'],
                    ['name' => 'AldimSpecialties'],
                ]],
                ['name' => 'Seeds', 'children' => [
                    ['name' => 'Seeds'],
                    ['name' => 'Sti'],
                ]],
                ['name' => 'Soups', 'children' => [
                    ['name' => 'Basak Soups'],
                    ['name' => 'Burcu Soups'],
                    ['name' => 'Knorr Soups'],
                    ['name' => 'Piyale Soups'],
                    ['name' => 'Podrovka/Vegeta Soups'],
                    ['name' => 'Various Soups'],
                ]],
                ['name' => 'Spices', 'children' => [
                    ['name' => 'Arabic Spices'],
                    ['name' => 'Basak Spices'],
                    ['name' => 'Dried Fruits'],
                    ['name' => 'Gülcan Spices'],
                    ['name' => 'Knorr Spices'],
                    ['name' => 'Öncu Spices'],
                    ['name' => 'Osmanli Spices'],
                    ['name' => 'Various Spices'],
                    ['name' => 'Vegeta'],
                ]],
                ['name' => 'Spreads', 'children' => [
                    ['name' => 'Argeta'],
                    ['name' => 'Asbal'],
                    ['name' => 'Buram'],
                    ['name' => 'Marmelade'],
                    ['name' => 'Pastes'],
                    ['name' => 'Syrup'],
                    ['name' => 'Various'],
                ]],
                ['name' => 'Sweets', 'children' => [
                    ['name' => 'Oruc Dede'],
                ]],
                ['name' => 'Tea and Coffee', 'children' => [
                    ['name' => 'Ahmad Tea'],
                    ['name' => 'Cofee and Milk Powder'],
                    ['name' => 'Dogadan Tea'],
                    ['name' => 'Doghazal tea'],
                    ['name' => 'Lipton tea'],
                    ['name' => 'Mahmood tea'],
                    ['name' => 'Tanay tea'],
                    ['name' => 'Tea Powders'],
                    ['name' => 'Various'],
                ]],
                ['name' => 'Vegetables', 'children' => [
                    ['name' => 'Cabbages'],
                    ['name' => 'Cucumber'],
                    ['name' => 'Lettuce'],
                    ['name' => 'Onions'],
                    ['name' => 'Paprika'],
                    ['name' => 'Potatoes'],
                    ['name' => 'Radish and beet'],
                    ['name' => 'Tomatoes'],
                    ['name' => 'Vegetables'],
                    ['name' => 'Zuccini and Eggplant'],
                ]],
                ['name' => 'Youghurt and Milk', 'children' => [
                    ['name' => 'Gazi'],
                    ['name' => 'Sütdıyarı'],
                    ['name' => 'Youghurt and Milk'],
                ]],
            ]]
        ];

        Category::rebuildTree($categories);
        Model::reguard();
    }
}
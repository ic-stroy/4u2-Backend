<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{

    public $sub_sub_clothes_men_categories = [
        "Pants",
        "Outerwear",
        "Jumpers, sweaters and cardigans",
        "Sexes",
        "Home clothes",
        "Carnival clothes",
        "T-shirts",
        "Underwear",
        "Socks and Gaiters",
        "Plus size clothing",
        "Jackets and suits",
        "Swimming trunks and shorts",
        "Religious clothing for men",
        "Dresses",
        "Special clothes",
        "Sports clothes",
        "Thermal underwear",
        "T-shirts and polos",
        "Hoodies and sweatshirts",
        "Shorts",
    ];
    public $sub_sub_clothes_women_categories = [
        "Blouses and shirts",
        "Body",
        "Pants",
        "Outerwear",
        "Knitted suits",
        "Jumpers, sweaters and cardigans",
        "Sexes",
        "Home clothes",
        "Carnival clothes",
        "Overalls",
        "Costumes and sets",
        "Swimwear and beachwear",
        "Underwear",
        "Socks, tights and socks",
        "Plus size clothing",
        "Clothes for pregnant women",
        "Jackets and suits",
        "Dresses and sundresses",
        "Religious clothing",
        "Special clothes",
        "Sports clothes",
        "Tops and T-shirts",
        "Tunics",
        "T-shirts",
        "T-shirts and polo shirts",
        "Hoodies and sweatshirts",
        "Shorts",
        "Skirts"
    ];
    public $sub_sub_clothes_boys_categories = [
        "Underwear and beachwear",
        "Pants and jeans",
        "Outerwear",
        "Jumpers, sweaters and cardigans",
        "Home clothes",
        "Overalls",
        "Suits and jackets",
        "Socks",
        "Shirts",
        "T-shirts",
        "Sports clothes",
        "Tolstovka and the Olympians",
        "School uniform for boys",
        "Shorts",
    ];
    public $sub_sub_clothes_girls_categories = [
        "Underwear and home clothes",
        "Blouses and shirts",
        "Pants and jeans",
        "Outerwear",
        "Jumpers, sweaters and cardigans",
        "Home clothes",
        "Overalls",
        "Suits and jackets",
        "Underwear, thermal underwear and swimwear",
        "Socks and pantyhose",
        "Dresses and sundresses",
        "Sports clothes",
        "Tolstovka and the Olympians",
        "T-shirts",
        "School uniform for girls",
        "Skirts and shorts",
    ];
    public $sub_sub_clothes_babies_categories = [
        "Bodysuits and overalls",
        "Outerwear",
        "Sweaters and sweatshirts",
        "Costumes and sets",
        "Underwear",
        "Socks, booties",
        "Dresses and skirts",
        "Trousers",
        "T-shirts, shirts and raspashonkas",
        "Caps, mittens and gloves",
    ];
    public $sub_sub_shoes_women_categories = [
        "Boots and ankle boots",
        "Shoes to wear at home",
        "Sneakers",
        'Uggs and boots',
        "Shlepans and slans",
        'Ballet flats and moccasins',
        'Sandals',
        'Sabo and Mule',
        'Shoes',
    ];
    public $sub_sub_shoes_men_categories = [
        "Boots and ankle boots",
        "Shoes to wear at home",
        "Sneakers",
        'Uggs and boots',
        "Sandals",
        "Shoes and moccasins",
    ];
    public $sub_sub_shoes_girls_categories = [
        "Boots and ankle boots",
        "Shoes to wear at home",
        "Sneakers",
        'Uggs and boots',
        "Ballet flats and shoes",
        "Sandals",
        "Boots",
    ];
    public $sub_sub_shoes_boys_categories = [
        "Boots and ankle boots",
        "Shoes to wear at home",
        "Sneakers",
        'Uggs and boots',
        "Ballet flats and shoes",
        "Sandals",
        "Boots",
        "Shoes and moccasins",
    ];
    public $sub_beauty_health_categories = [
        [
            'name'=>"Aromatherapy",
            'sub_sub_category'=>[]
        ],
        [
            'name'=>"For men",
            'sub_sub_category'=>[
                "For beards and mustaches",
                "For hair",
                "Face and body care",
            ],
        ],
        [
            'name'=>"Korean cosmetics",
            'sub_sub_category'=>[
                "Facial skin cleansers",
                "Body cleansers",
                "Hair care products",
                "Facial care products",
                "Body care products",
            ],
        ],
        [
            'name'=>"Personal hygiene",
            'sub_sub_category'=>[
                "Paper and cotton products",
                "Oral hygiene",
                "Deodorants and antiperspirants",
                "Depilation and Epilation",
                "Intimate hygiene",
                "Soap",
                "Shaving products",
            ],
        ],
        [
            'name'=>"Makeup",
            'sub_sub_category'=>[
                "Makeup accessories",
                "Eyebrows",
                "Eyes",
                "Lips",
                "Face",
            ],
        ],
        [
            'name'=>"Manicure and pedicure",
            'sub_sub_category'=>[
                "Bases and Balls",
                "Gel-lacquers",
                "Nail Design",
                "Manicure accessories",
                "Varnishes",
                "Healing and care products",
                "Materials for gluing nails",
                "Primers and Degreasers",
                "Nail Polish Removers",
            ],
        ],
        [
            'name'=>"Collections",
            'sub_sub_category'=>[
                "Cosmetic sets for hair",
                "Makeup kits",
                "Manicure and pedicure sets",
                "Cosmetic sets for body care",
                "A set of cosmetics for men",
                "Facial kits",
                "Perfume collection",
            ],
        ],
        [
            'name'=>"Equipment and supplies for the tattoo parlor",
            'sub_sub_category'=>[
                "Equipment for piercing",
                "Cartridges",
                "Tattoo machines and accessories",
                "Tattoo kits",
                "Tattoo care",
            ],
        ],
        [
            'name'=>"Perfume",
            'sub_sub_category'=>[
                "Aromatic oils",
                "Atomizers",
                "Perfumes",
                "Oily perfumes",
                "Dry perfumes",
                "Fragrant water",
                "Miniatures and plugs",
                "Colognes",
                "Perfumed water",
                "Selective perfumery",
                "Flavored water",
                "Vials for perfumes",
            ],
        ],
        [
            'name'=>"Professional cosmetics",
            'sub_sub_category'=>[
                "Makeup",
                "For men",
                "Hair care",
                "Facial care",
                "Body care",
            ],
        ],
        [
            'name'=>"Hair care",
            'sub_sub_category'=>[
                "Accessories",
                "Dyeing and chemical curling",
                "Professional tools for hairdressers",
                "Cleaning and care products",
                "Tools for styling",
            ],
        ],
        [
            'name'=>"Facial care",
            'sub_sub_category'=>[
                "Masks",
                "Cleaning and washing",
                "Patches",
                "Scrub and exfoliate",
                "Products for problem skin",
                "Moisturizing and nourishing",
                "For lip care",
            ],
        ],
        [
            'name'=>"Body care",
            'sub_sub_category'=>[
                "Accessories",
                "Sun exposure and sun protection",
                "Tools for shower and bath",
                "Anti-cellulite and stretch marks",
                "Moisturizing and nourishing",
            ],
        ],
    ];
    public $sub_accessories = [
        [
            'name'=>'Accessories for adult',
            'sub_sub_category'=>[]
        ],
        [
            'name'=>"Accessories for adults",
            'sub_sub_category'=>[
                "Hair accessories",
                "Bijouterie",
                "Headwear",
                "Umbrellas",
                "Purses for girls",
                "Tableware",
                "Glasses",
                "Gloves and mittens",
                "Belts",
                "Bags and backpacks",
                "Scarves and shawls",
            ]
        ],
        [
            'name'=>"Accessories for girls",
            'sub_sub_category'=>[
                "Bijouterie",
                "Neckties and bow ties, handkerchiefs",
                "Headwear",
                "Umbrellas",
                "Wallets for boys",
                "Glasses",
                "Gloves and mittens",
                "Straps and suspenders",
                "Bags and backpacks",
                "Scarves and shawls",
            ]
        ],
        [
            'name'=>"Accessories for boys",
            'sub_sub_category'=>[
                "Accessories for suitcases",
                "Travel bags",
                "Travel kits",
                "Travel organizers",
                "Goods for sleep",
                "Suitcases",
            ]
        ],
        [
            'name'=>"Travel accessories",
            'sub_sub_category'=>[
                "Hair accessories",
                "Bijouterie",
                "Headwear",
                "Umbrellas",
                "Pockets for wallets, keyrings and business cards",
                "Glasses",
                "Gloves and mittens",
                "Handkerchiefs and scarves",
                "Belts, belts and portupeis",
                "Wedding accessories",
                "Bags and backpacks",
                "Watches and straps",
                "Jewelry",
            ]
        ],
        [
            'name'=>"Women's accessorie",
            'sub_sub_category'=>[
                "Bijouterie",
                "Neckties, bow ties, handkerchiefs",
                "Headwear",
                "Umbrellas",
                "Pockets for wallets, keyrings and business cards",
                "Glasses",
                "Gloves and mittens",
                "Handkerchiefs and scarves",
                "Straps and suspenders",
                "Bags and backpacks",
                "Watches and straps",
                "Jewelry",
            ]
        ],
        [
            'name'=>"Men's accessories",
            'sub_sub_category'=>[
                "Pendants and necklaces",
                "Hats",
                "Hijabs and turbans",
                "Rosary",
            ]
        ]
    ];

    public $sub_toys = [
        [
            'name'=>"Wooden toys",
            'sub_sub_category'=>[]
        ],
        [
            'name'=>"For children under 3 years old",
            'sub_sub_category'=>[]
        ],
        [
            'name'=>"Toy guns and blasters",
            'sub_sub_category'=>[]
        ],
        [
            'name'=>"Toy transport",
            'sub_sub_category'=>[]
        ],
        [
            'name'=>"Toys - antistress",
            'sub_sub_category'=>[]
        ],
        [
            'name'=>"Constructors",
            'sub_sub_category'=>[]
        ],
        [
            'name'=>"Dolls and accessories",
            'sub_sub_category'=>[]
        ],
        [
            'name'=>"Dollhouses",
            'sub_sub_category'=>[]
        ],
        [
            'name'=>"Soft toys",
            'sub_sub_category'=>[]
        ],
        [
            'name'=>"Radio-controlled models and toys",
            'sub_sub_category'=>[]
        ],
        [
            'name'=>"Educational and developmental toys",
            'sub_sub_category'=>[]
        ],
        [
            'name'=>"Robotics and Stem-toys",
            'sub_sub_category'=>[]
        ],
        [
            'name'=>"Slimes",
            'sub_sub_category'=>[]
        ],
        [
            'name'=>"Figures and accessories",
            'sub_sub_category'=>[]
        ],
        [
            'name'=>"Storage of toys",
            'sub_sub_category'=>[]
        ],
    ];
    public $medicine = [
        [
          'name'=> "Vitamins, BFQs, nutritional supplements",
          'sub_sub_category'=>[]
        ],
        [
          'name'=> "BFQs",
          'sub_sub_category'=>[]
        ],
        [
          'name'=> "Vitamin and mineral complexes",
          'sub_sub_category'=>[]
        ],
        [
          'name'=> "Vitamins",
          'sub_sub_category'=>[]
        ],
        [
          'name'=> "Special meals",
          'sub_sub_category'=>[]
        ],
        [
          'name'=> "Spirulina",
          'sub_sub_category'=>[]
        ],
    ];

    public function run(): void
    {
        $categories = [
            [
                'name'=>'Shoes',
                'sub_category'=> [
                    [
                        'name'=>'Women',
                        'sub_sub_category'=>$this->sub_sub_shoes_women_categories,
                    ],
                    [
                        'name'=>'Men',
                        'sub_sub_category'=>$this->sub_sub_shoes_men_categories,
                    ],
                    [
                        'name'=>'Boys',
                        'sub_sub_category'=>$this->sub_sub_shoes_boys_categories,
                    ],
                    [
                        'name'=>'Girls',
                        'sub_sub_category'=>$this->sub_sub_shoes_girls_categories,
                    ],
                ],
            ],
            [
                'name'=>'Clothes',
                'sub_category'=> [
                    [
                        'name'=>'Women',
                        'sub_sub_category'=>$this->sub_sub_clothes_women_categories,
                    ],
                    [
                        'name'=>'Men',
                        'sub_sub_category'=>$this->sub_sub_clothes_men_categories,
                    ],
                    [
                        'name'=>'Boys',
                        'sub_sub_category'=>$this->sub_sub_clothes_boys_categories,
                    ],
                    [
                        'name'=>'Girls',
                        'sub_sub_category'=>$this->sub_sub_clothes_girls_categories,
                    ],
                    [
                        'name'=>'Babies',
                        'sub_sub_category'=>$this->sub_sub_clothes_babies_categories,
                    ],
                ],
            ],
            [
                'name'=>'Beauty and healty',
                'sub_category'=> $this->sub_beauty_health_categories
            ],
            [
                'name'=>'Medicine and vitamins',
                'sub_category'=> $this->medicine
            ],
            [
                'name'=>'Accessories',
                'sub_category'=> $this->sub_accessories
            ],
            [
                'name'=>'Toys',
                'sub_category'=> $this->sub_toys
            ]
        ];
        $category_id = Category::withTrashed()->select('id')->orderBy('id', 'desc')->first();
        if(!$category_id){
            $all_categories = [];
            $all_sub_categories = [];
            $all_sub_sub_categories = [];
            $category_id_ = $category_id?$category_id->id:0;
            $sub_category_id_ = 0;
            $sub_sub_category_id_ = 0;
            $last_category_id = -1;
            foreach ($categories as $category){
                $category_id_++;
                $all_categories[] = [
                    'id' => (int)$category_id_,
                    'name' => $category['name'],
                    'step' => 0, 'parent_id' => null
                ];
                if($last_category_id < $sub_category_id_){
                    $sub_category_id_ = $category_id_ + count($categories)-1;
                }else{
                    $sub_category_id_ = $last_category_id;
                }
                foreach ($category['sub_category'] as $sub_category){
                    if($sub_sub_category_id_ != 0){
                        $sub_category_id_ = $sub_sub_category_id_;
                    }
                    $sub_category_id_++;
                    $last_category_id = $sub_category_id_;
                    $all_sub_categories[] = [
                        'id'=>(int)$sub_category_id_,
                        'name'=>$sub_category['name'],
                        'step'=>1, 'parent_id'=>$category_id_
                    ];
                    $sub_sub_category_id_ = $sub_category_id_;
                    foreach ($sub_category['sub_sub_category'] as $sub_sub_category){
                        $sub_sub_category_id_++;
                        $last_category_id = $sub_sub_category_id_;
                        $all_sub_sub_categories[] = [
                            'id'=>(int)$sub_sub_category_id_,
                            'name'=>$sub_sub_category,
                            'step'=>2, 'parent_id'=>$sub_category_id_
                        ];
                    }
                }
            }
            $all_categories_ = array_merge($all_categories, $all_sub_categories, $all_sub_sub_categories);
            DB::table('categories')->insert($all_categories_);
        }else{
            $category_deleted_at = Category::withTrashed()->select('deleted_at')->find($category_id->id);
            if($category_deleted_at->deleted_at){
                echo "Category is exist status deleted";
            }else{
                echo "Category is exist status active";
            }
        }
    }
}

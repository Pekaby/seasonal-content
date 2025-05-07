<?php 

namespace SeasonalContent\Components\Category;


class CategoryComponent implements \SeasonalContent\Components\Component
{
    const NAME = 'CategoryComponent';

    public array $categories = [];
    private int $activeCategory = 0;
    

    public static function getCategories() {
        global $wpdb;

        $categories = $wpdb->get_results("SELECT * FROM " . esc_sql($wpdb->prefix . SEASONALCONTENT_PREFIX . 'categories'));

        $m_categories = [];
        foreach ($categories as $category) {
            $m_categories[] = \SeasonalContent\Models\Category::init()->setTitle($category->title)
                                                                    ->setSlug(\SeasonalContent\Support\Translitiration::translit($category->title))
                                                                    ->setStartDate($category->date_start)
                                                                    ->setEndDate($category->date_end)
                                                                    ->setCreatedBy($category->created_by)
                                                                    ->setCreatedAt($category->created_at)
                                                                    ->setUpdatedAt($category->updated_at)
                                                                    ->setId((int) $category->id);
        }

        return $m_categories;
    }

    public function saveCategories() {
        global $wpdb;

        foreach ($this->categories as &$category) {
            if($category->id > 0) {
                $wpdb->update($wpdb->prefix . SEASONALCONTENT_PREFIX . 'categories', (array) $category, ['id' => $category->id]);
                continue;
            }
            unset($category->id);
            $new_category = $wpdb->insert($wpdb->prefix . SEASONALCONTENT_PREFIX . 'categories', (array) $category);
            $category->id = ($new_category) ? $wpdb->insert_id : 0;


        }
        $this->updateCurrentSeason();
    }

    public function updateCurrentSeason():void {
        update_option(SEASONALCONTENT_PREFIX . "current_season", 0);
        $this->activeCategory = 0;
        foreach ($this->categories as $category) {
            $year_start = 1970;
            $year_end = ( (new \DateTime($category->date_start))->format('m') == 12 && (new \DateTime($category->date_end))->format('m') != 12 ) ? ($year_start + 1) : $year_start;

            $today = new \DateTime('now');
            $today = \DateTime::createFromFormat('Y-m-d', $year_end . '-' .$today->format('m-d'));

            $date_start = \DateTime::createFromFormat('Y-m-d', $year_start . '-'.(new \DateTime($category->date_start))->format('m-d'));
            $date_end = \DateTime::createFromFormat('Y-m-d', $year_end . '-'.(new \DateTime($category->date_end))->format('m-d'));

            if($today >= $date_start && $today <= $date_end) {
                // var_dump($date_start);
                // var_dump($date_end);
                // var_dump($category);
                $this->activeCategory = $category->id;
                update_option(SEASONALCONTENT_PREFIX . "current_season", $category->id);
            } 
        }
    }

    public static function getCategory($id):\SeasonalContent\Models\Category {
        global $wpdb;
        $table = esc_sql($wpdb->prefix . SEASONALCONTENT_PREFIX . "categories");
        $category = $wpdb->get_results($wpdb->prepare("SELECT * FROM `{$table}` WHERE `id` = %d", $id));
        if($category){
            $category = $category[0];
            return \SeasonalContent\Models\Category::init()->setTitle($category->title)
                                   ->setSlug(\SeasonalContent\Support\Translitiration::translit($category->title))
                                   ->setStartDate($category->date_start)
                                   ->setEndDate($category->date_end)
                                   ->setCreatedBy($category->created_by)
                                   ->setCreatedAt($category->created_at)
                                   ->setUpdatedAt($category->updated_at)
                                   ->setId((int) $category->id);
        }
        return \SeasonalContent\Models\Category::init();
    }

    public static function updateCurrentCategory():CategoryComponent {
        $categoryComponent = new \SeasonalContent\Components\Category\CategoryComponent();
        $categoryComponent->categories = \SeasonalContent\Models\Category::getAllCategories();
        $categoryComponent->updateCurrentSeason();
        return $categoryComponent;
    }

}
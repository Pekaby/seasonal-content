<?php 

namespace SeasonalContent\Models;


class Category
{
    public int $id;

    public $title;
    public $slug;
    public $date_start;
    public $date_end;

    public $created_by;

    public $updated_at;
    public $created_at;

    public static function getAllCategories():array {
        global $wpdb;
        $talbeName = esc_sql($wpdb->prefix . SEASONALCONTENT_PREFIX . 'categories');
        $sql = "SELECT * FROM `{$talbeName}`";
        $categories_raw = $wpdb->get_results( $sql );

        if(!$categories_raw) return [];

        $categories = [];
        foreach ($categories_raw as $key => $category) {
            $categories[] = self::init()->setTitle($category->title)
                                        ->setSlug(\SeasonalContent\Support\Translitiration::translit($category->title))
                                        ->setStartDate($category->date_start)
                                        ->setEndDate($category->date_end)
                                        ->setCreatedBy($category->created_by)
                                        ->setCreatedAt($category->created_at)
                                        ->setUpdatedAt($category->updated_at)
                                        ->setId((int) $category->id);
        }

        return $categories;

    }

    public static function getCategory($id):self {
        global $wpdb;
        $table = esc_sql( $wpdb->prefix . SEASONALCONTENT_PREFIX . "categories" );
        $category = $wpdb->get_results( 
            $wpdb->prepare(
                "SELECT * FROM `{$table}` WHERE `id` = %d",
                $id
            )
        );
        if(!$category) return self::init();

        return self::init()->setTitle($category[0]->title)
                           ->setSlug(\SeasonalContent\Support\Translitiration::translit($category[0]->title))
                           ->setStartDate($category[0]->date_start)
                           ->setEndDate($category[0]->date_end)
                           ->setCreatedBy($category[0]->created_by)
                           ->setCreatedAt($category[0]->created_at)
                           ->setUpdatedAt($category[0]->updated_at)
                           ->setId((int) $category[0]->id);
    }

    public static function init():self {
        return new self();
    }

    public function delete() {
        global $wpdb;
        if(isset($this->id)) {
            $wpdb->delete(
                esc_sql($wpdb->prefix.SEASONALCONTENT_PREFIX."categories"),
                [
                    "id" => $this->id
                ]
            );
        }
    }

    public function setId($id):self {
        $this->id = (!is_int($id)) ? 0 : $id;
        return $this;
    }

    public function setTitle($title):self {
        $this->title = $title;
        return $this;
    }

    public function setSlug($slug):self {
        $this->slug = $slug;
        return $this;
    }

    public function setStartDate($date_start):self {
        $this->date_start = $date_start;
        return $this;
    }

    public function setEndDate($date_end):self {
        $this->date_end = $date_end;
        return $this;
    }

    public function setCreatedBy($user_id):self {
        $this->created_by = $user_id;
        return $this;
    }

    public function setUpdatedAt($datetime):self {
        $this->updated_at = $datetime;
        return $this;
    }

    public function setCreatedAt($datetime):self {
        $this->created_at = $datetime;
        return $this;
    }

}
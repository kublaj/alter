<?php
/**
 * Created by PhpStorm.
 * User: Desenvolvimento
 * Date: 07/07/14
 * Time: 18:04
 */

class Model {

	private $post_type;
	private $appModel;

	public function __construct($appModel){
		$this->post_type = $appModel->getPostType();
		$this->appModel = $appModel;
	}

    /**
     * Automagic Methods
     * @param $method
     * @param $arguments array, first value is the value to find, the second is a query
     * @return bool|WP_Query
     * @throws Exception
     */
    function __call($method, $arguments){

        $qr = array();

        $non_custom_allowed = array('id', 'status', 'category', 'author', 'date');

        $findvalue = $arguments[0];

        if(!empty($arguments[1]) && count($arguments[1]) > 0){

            foreach($arguments[1] as $f => $v){
                $qr[$f] = $v;
            }

        }


        $custom_fields = array();
        foreach($this->appModel->getFields() as $field => $value){
            if(is_array($value)) $custom_fields[$field] = $value;
        }

        $attribute = str_replace("find_by_", "", $this->from_camel_case($method));

        if(in_array($attribute, $non_custom_allowed)){

            $key = null;

            switch($attribute){

                case 'id':
                    $key = 'p';
                    break;

                case 'status':
                    $key = 'post_status';
                    break;

                case 'category':
                    $key = 'cat';
                    break;

                case 'author':
                    $key = 'author';
                    break;

                case 'date':
                    $key = 'date_query';
                    break;

            }

            $qr[$key] = $findvalue;

            return $this->find($qr);

        }else{

            if(!empty($custom_fields[$attribute])){

                $qr['meta_key'] = $attribute;
                $qr['meta_value'] = $findvalue;

                return $this->find($qr);

            }else{

                throw new Exception("Trying to access a method that doesn't exists");

            }

        }

    }

    /**
	 * Find posts in the Wordpress database using WP_Query
	 *
	 * @param $options
	 * @return bool|WP_Query
	 */
	public function find($options = null){

		try{

			$attrs = $this->buildQuery($options);

			if(empty($attrs['limit'])){
				$attrs['limit'] = -1;
			}

            if(!empty($attrs['p'])){
                return new PostObject(get_post($attrs['p']), $this->appModel);
            }

			$qr = new WP_Query($attrs);

			if(!$qr->have_posts()){
				throw new NoPostFoundException();
			}else{

				$posts = array();

				while($qr->have_posts()){

					$qr->the_post();

					$obj = new PostObject(get_post(get_the_ID()), $this->appModel);
					array_push($posts, $obj);

				}

				return $posts;

			}

		}catch(NoPostFoundException $e){
			return false;
		}

	}

	/**
	 * Find a post by the slug
	 * @param $slug
	 * @return bool|PostObject
	 */
	public function findBySlug($slug){

		$args = array(
			'name' => $slug,
			'post_type' => $this->post_type,
			'post_status' => 'publish',
			'numberposts' => 1
		);

		$my_posts = get_posts($args);

		if( $my_posts ) {
			return $this->findById($my_posts[0]->ID);
		}else{
			return false;
		}

	}

	/**
	 * Find posts by a taxonomy
	 * @param $taxonomy
	 * @param $value
	 * @param $limit
	 * @return bool|WP_Query
	 */
	public function findByTaxonomy($taxonomy, $value, $limit){

		$options = array();

		if(empty($limit)){
			$limit = get_option('posts_per_page');
		}

		$options['posts_per_page'] = $limit;

		$options['tax_query'] = array(
			array(
				'taxonomy' => $taxonomy,
				'field' => 'slug',
				'terms' => $value
			)
		);

		return $this->find($options);

	}

	/**
	 * Paginate a list of posts
	 * @param null $limit
	 * @param null $paged
	 * @return bool|WP_Query
	 */
	public function paginate($limit = null, $paged = null){

		if($paged == null){
			$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
		}

		if(empty($limit)){
			$limit = get_option('posts_per_page');
		}

		return $this->find(array('limit' => $limit, 'query' => 'paged='.$paged));

	}

	/**
	 * Creates a quer for WP_Query
	 * @param null $options
	 * @return array
	 */
	private function buildQuery($options = null){

		$attrs = $this->getDefaultQuery();

		if(!empty($options)){

			// If is int means that it's a limit parameter
			if(is_int($options)){
				$options = array('limit' => $options);
			}

			// But if is a array with the 'limit' index, too
			if(!empty($options['limit'])){
				$attrs['posts_per_page'] = $options['limit'];
			}

			// Check if is arguments for WP_Query
			if(is_array($options)){
				foreach($options as $key => $value){
					$attrs[$key] = $value;
				}
			}

			// Or if is arguments for WP_Query into 'query' index
			if(!empty($options['query'])){

				$arr = explode('&', $options['query']);

				foreach($arr as $item){

					$arr_item = explode('=', $item);
					$attrs[$arr_item[0]] = $arr_item[1];

				}

			}

		}

		return $attrs;

	}

	/**
	 * Create a default query
	 *
	 * @return array
	 */
	private function getDefaultQuery(){

		return array(
			'post_type'     => $this->post_type,
			'post_status'   => 'publish'
		);

	}

    /**
     * Extracts method name
     * @param $str
     * @return mixed
     */
    private function from_camel_case($str) {
        $str[0] = strtolower($str[0]);
        $func = create_function('$c', 'return "_" . strtolower($c[1]);');
        return preg_replace_callback('/([A-Z])/', $func, $str);
    }

    public function getAppModel(){
    	return $this->appModel;
    }

} 
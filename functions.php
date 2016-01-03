<?php


add_filter( 'the_content', 'show_restaurant_details' );
function show_restaurant_details( $content ) {
    $review = '';


    if( has_category( 'restaurant-review' ) ) {
        //we retrieve the custom fields
        $pricing = get_field( 'pricing' );
        $menu_pricing = get_field( 'menu_pricing' );
        $quantities = get_field( 'quantities' );
        $chain = get_field( 'chain' );
        $goodness = get_field( 'goodness' );
        $video_review = get_field( 'video_review' );
        $rare_dishes = get_field('rare_dishes');

    	$type_of_establishment = get_field_object('type_of_establishment');
		$type_of_establishment_value = get_field('type_of_establishment');
		$type_of_establishment_label = $type_of_establishment['choices'][ $type_of_establishment_value ];

        if ($menu_pricing > 0 || $pricing > 0 ||  $quantities > 0 || $goodness > 0 ) {
            $review = "<table class=\"review_table\">";
            $review .=
                "<tr>
                    <td style=\"border: none\">Type of establishment: " . $type_of_establishment_label . "</td>
                </tr>";

            /* pricing */
            if ($pricing > 0) {
                $review .=
                '<tr>
                    <td >Pricing: <span style="color:' . get_field_color($pricing, true) . '">' . str_repeat("$",$pricing) . '</span></td>
                </tr>';

            }

            /* menu pricing */
            if ($menu_pricing > 0) {
                $review .=
                '<tr>
                    <td >Menu pricing: <span style="color:' . get_field_color($menu_pricing, true) . '">' . str_repeat("$",$menu_pricing) . '</span></td>
                </tr>';

            }

            /* goodness */
            if ($goodness > 0) {
                $review .=
                '<tr>
                    <td >Goodness: <span style="color:' . get_field_color($goodness) . '">' . str_repeat("<i class=\"fa fa-thumbs-o-up\"></i>",$goodness) . '</span></td>
                </tr>';

            }

            /* goodness */
            if ($quantities > 0) {
                $review .=
                '<tr>
                    <td >Quantities: <span style="color:' . get_field_color_quantities($quantities) . '">' . str_repeat("<i class=\"fa fa-thumbs-o-up\"></i>",$quantities) . '</span></td>
                </tr>';

            }

            if ($rare_dishes) {
                $plur_dishes = count($rare_dishes) > 1 ? "dishes" : "dish";
                $review .= "<tr><td>The following recommended and rare $plur_dishes can be found here: ";
                //we iterate over the dishes
                foreach ($rare_dishes as $rare_dish) {
                    setup_postdata($rare_dish);
                    if ($rare_dish_str != "") {$rare_dish_str .= ", ";}
                    $rare_dish_str .= "<a href=\"" . get_permalink( $rare_dish->ID ) . "\">" . get_the_title( $rare_dish->ID ) . "</a>";
                }
                $review .= $rare_dish_str . '</td></tr>';
                wp_reset_postdata();
            }


            $review .= "</table>";

        }
    	
     	
    }

    return $review . $content;
}

add_filter( 'the_content', 'show_dish_details' );
function show_dish_details( $content ) {
    $review = '';

    if( has_category( 'dish-review' ) ) {
    	//we retrieve the custom fields
        $pleasure = get_field( 'pleasure' );
    	$fatness = get_field( 'fatness' );
    	$pricing = get_field( 'pricing' );
        $warning = get_field( 'warning' );
        $supermarket_product = get_field( 'supermarket_product' );
        $vegetarian = get_field( 'vegetarian');
        $vegan = get_field( 'vegan' );
        $halal = get_field( 'halal' );
        $kosher = get_field( 'kosher' );
        $video_review = get_field( 'video_review' );

        if ($fatness > 0 || $pricing > 0 ||  $pleasure > 0 || $vegetarian . "" != "" || $vegan . "" != "" || $halal . "" != "" || $kosher . "" != "") {
            $review = '<table class="review_table">';
            /* pleasure . max = 7 */
            if ($pleasure > 0) {
                $pleasure_icons = "<span  style=\"font-weight: 400;font-family: Arial, sans-serif; color:" . get_field_color($pleasure) . ";\">" . str_repeat("P",$pleasure); 
                $pleasure_icons .= "</span>";
                $review .= '<tr>
                                <td style="border-bottom: none">Pleasure: ' . $pleasure_icons . ' (' . $pleasure . '/7)</td>';

                if ($warning) {
                    $review .= '(<span style="color: red">*</span>)';
                }

                $review .= '</tr>';
            }

            /* fatness . max = 8 */
            if ($fatness > 0) {
                $fatness_icons = "<span title=\"font-fatness: " . $fatness*100 . "\"  style=\"font-weight: " . $fatness*100 . ";font-family: Arial, sans-serif; color:" . get_field_color($fatness, true) . ";\">" . str_repeat("F",$fatness); 
                $fatness_icons .= "</span>";
                $review .= '<tr>
                                <td>Fatness: ' . $fatness_icons . ' (' . $fatness . '/7)</td>
                            </tr>';
            }

            /* pleasure/fatness ratio */
            if ($fatness > 0 && $pleasure > 0) {
                if ($pleasure == $fatness) {
                    $pleasure_fatness = "1";
                } else {
                    $pleasure_fatness = $pleasure / $fatness;
                }
                
                $review .= '<tr>
                                <td>Pleasure/fatness ratio: <span style="color:' . get_field_color_pleasure_fatness_ratio($pleasure_fatness) . '">' . sprintf("%.2f",$pleasure_fatness) . '</span></td>
                            </tr>';
            }

            /* pricing */
            if ($pricing > 0) {
                $review .=
                '<tr>
                    <td >Pricing: <span style="color:' . get_field_color($pricing, true) . '">' . str_repeat("$",$pricing) . '</span></td>
                </tr>';

            }

            /* supermarket product */
            $supermarket_product_literal = ($supermarket_product ? "Supermarket product" : "Regular dish");
            $review .=
            '<tr>
                <td >' . $supermarket_product_literal . '</td>
            </tr>';
            
            $review .= '</table>';

            /* vegetarian dish */
            if ($vegetarian . "" != "") {

                
                $review .= '<tr>
                                <td>Vegetarian: ' . $vegetarian . '</td>
                            </tr>';
            }

            /* vegan dish */
            if ($vegan . "" != "") {

                
                $review .= '<tr>
                                <td>Vegan: ' . $vegan . '</td>
                            </tr>';
            }

            /* halal dish */
            if ($halal . "" != "") {

                
                $review .= '<tr>
                                <td>Halal: ' . $halal . '</td>
                            </tr>';
            }

            /* kosher dish */
            if ($kosher . "" != "") {

                
                $review .= '<tr>
                                <td>Kosher: ' . $kosher . '</td>
                            </tr>';
            }

            $review .= "</table>";
        }
    	    
       	
    }

    return $review . $content;
}

/*this appends the overall rating to every review*/
add_filter( 'the_content', 'append_overall_rating' );
function append_overall_rating( $content ) {
	$o_rating_table = "";
	if( has_category( 'review' ) ) { 
		$overall_rating = get_field( 'overall_rating' );
		if ($overall_rating > 0) {
			//we open the table
			$o_rating_table .= "<table class=\"review_table\"><tbody><tr><td>Overall rating: <span style=\"color: #CCCC00;\">";
			//max overall rating: 7
			$o_rating_table .= str_repeat('<i class="fa fa-star"></i>',$overall_rating);	
			$o_rating_table .= str_repeat('<i class="fa fa-star-o"></i>',7-$overall_rating) ;	
	    	//now close the table
			$o_rating_table .= "</span> (" . $overall_rating . "/7)</td></tr></tbody></table>";
		}
	}
	return $content . $o_rating_table;
}

function get_field_color($rating, $inverted = false) {
    if (!$inverted) {
        switch ($rating) {
            case 1:
                return "#FF0000";
                break;
            case 2:
                return "#FF3300";
                break;
            case 3:
                return "#FF9933";
                break;
            case 4:
                return "#FFCC00";
                break;
            case 5:
                return "#3DF400";
                break;
            case 6:
                return "#33CC33";
                break;
            case 7:
                return "#009933";
                break;          
        } 
    } else {
        switch ($rating) {
            case 1:
                return "#009933";
                break;
            case 2:
                return "#33CC33";
                break;
            case 3:
                return "#3DF400";
                break;
            case 4:
                return "#FFCC00";
                break;
            case 5:
                return "#FF9933";
                break;
            case 6:
                return "#FF3300";
                break;
            case 7:
                return "#FF0000";
                break;
            /* fatness only */
            case 8:
                return "#800000";
                break;
                
        } 

    }
    return "#000";
}

function get_field_color_quantities($quant) {
    switch ($quant) {
        case 1:
            return "#FF0000";
            break;
        case 2:
            return "#0000FF";
            break;
        case 3:
            return "#33CC33";
            break;
        case 4:
            return "#009933";
            break;          
    } 
    return "#000";

}

function get_field_color_pleasure_fatness_ratio($ratio) {
    $rati = $ratio;
    if ($rati < 1) {
        $rati = floor($rati * 7);
        switch ($rati) {
            case 1:
                return "#b30000";
                break;
            case 2:
                return "#cc0000";
                break;
            case 3:
                return "#e60000";
                break;
            case 4:
                return "#ff0000";
                break;
            case 5:
                return "#ff0040";
                break;
            case 6:
                return "#bf00ff";
                break;
            case 7:
                return "#6600ff";
                break;          
        } 
        
    }
    switch ($rati) {
        case 1:
            return "#0000FF";
            break;
        case 2:
            return "#006666";
            break;
        case 3:
            return "#009999";
            break;
        case 4:
            return "#00cc99";
            break;
        case 5:
            return "#00cc66";
            break;
        case 6:
            return "#00cc00";
            break;
        case 7:
            return "#009933";
            break;          
    } 
    return "#000";
}

function theme_enqueue_styles() {

    $parent_style = 'twentysixteen';

    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'twentysixteen-atastertastes',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style )
    );


    //Add Font Awesome
    wp_enqueue_style($parent_style, get_template_directory_uri() . '/font-awesome/css/font-awesome.css', array(), '4.4.0'); 
    wp_enqueue_style( 'font-awesome',
        get_stylesheet_directory_uri() . '/font-awesome/css/font-awesome.css',
        array( $parent_style )
    );
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );

?>
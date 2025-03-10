<?php
/**
 * Plugin Name: Custom Product Search
 * Description: Adds a shortcode for custom product search functionality
 * Version: 1.0
 * Author: Your Name
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class Custom_Product_Search {
    
    /**
     * Constructor
     */
    public function __construct() {
        // Register shortcode
        add_shortcode('custom_product_search', array($this, 'render_search_form'));
        
        // Register shortcode for search results
        add_shortcode('custom_product_search_results', array($this, 'render_search_results'));
        
        // Add CSS to head
        add_action('wp_head', array($this, 'add_search_styles'));
        
        // Register AJAX handler for search
        add_action('wp_ajax_custom_product_search', array($this, 'process_search'));
        add_action('wp_ajax_nopriv_custom_product_search', array($this, 'process_search'));
    }
    
    /**
     * Add search styles to head
     */
    public function add_search_styles() {
        ?>
        <style>
            /* Full width container reset */
            .header-search-form-wrapper {
                width: 100% !important;
                max-width: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            
            /* Search bar styles */
            .custom-search-container {
                width: 100%;
                padding: 0;
                margin: 0 auto;
                box-sizing: border-box;
                max-width: 800px; /* Increased width */
            }
            
            /* For center column placement */
            .flex-center .custom-search-container,
            .center .custom-search-container,
            .has-center .custom-search-container,
            .has-center-logo .custom-search-container {
                width: 100%;
                max-width: 800px; /* Increased width */
                margin: 0 auto;
            }
            
            .custom-search-container form {
                display: flex;
                width: 100%;
                margin: 0;
            }
            
            .custom-search-container input[type="text"] {
                border: 1px solid #ddd;
                padding: 10px 15px;
                width: 100%;
                flex-grow: 1;
                border-radius: 4px 0 0 4px;
                font-size: 16px;
                outline: none;
                transition: border-color 0.3s;
                margin: 0;
                height: 42px;
                box-sizing: border-box;
            }
            
            .custom-search-container input[type="text"]:focus {
                border-color: #4CAF50;
            }
            
            .custom-search-container button {
                padding: 10px 20px;
                background: #4CAF50;
                color: white;
                border: none;
                border-radius: 0 4px 4px 0;
                cursor: pointer;
                font-size: 16px;
                transition: background 0.3s;
                margin: 0;
                height: 42px;
                box-sizing: border-box;
                line-height: 1;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .custom-search-container button:hover {
                background: #45a049;
            }
            
            /* Results styles */
            .search-results-container {
                padding: 20px;
                max-width: 1200px;
                margin: 0 auto;
            }
            
            .product-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
                gap: 20px;
                padding: 20px 0;
            }
            
            .product-item {
                border: 1px solid #eee;
                border-radius: 8px;
                overflow: hidden;
                transition: transform 0.2s;
                background: white;
                display: flex;
                flex-direction: column;
                position: relative;
            }
            
            .product-item:hover {
                transform: translateY(-5px);
                box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            }
            
            .product-image {
                width: 100%;
                height: 200px;
                overflow: hidden;
            }
            
            .product-image img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: transform 0.3s;
            }
            
            .product-image img:hover {
                transform: scale(1.05);
            }
            
            .no-image {
                width: 100%;
                height: 100%;
                background: #f5f5f5;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #666;
            }
            
            .product-details {
                padding: 10px;
                text-align: center;
            }
            
            .product-details h4 {
                margin: 5px 0;
                font-size: 14px;
                line-height: 1.3;
                height: 36px;
                overflow: hidden;
            }
            
            .product-details h4 a {
                color: #333;
                text-decoration: none;
            }
            
            .price {
                font-weight: bold;
                color: #e44d26;
                margin: 5px 0;
            }
            
            .category {
                font-size: 12px;
                color: #666;
                margin: 5px 0;
            }
            
            .view-details {
                display: inline-block;
                padding: 5px 15px;
                background: #4CAF50;
                color: white !important;
                text-decoration: none;
                border-radius: 4px;
                margin-top: 5px;
                font-size: 12px;
            }
            
            .view-details:hover {
                background: #45a049;
            }
            
            .no-results, .error-message {
                text-align: center;
                padding: 20px;
                background: #f8f8f8;
                border-radius: 8px;
                margin: 20px auto;
                max-width: 600px;
            }
            
            .error-message {
                background: #fee;
                color: #c00;
            }
            
            /* Source badges */
            .source-badge {
                position: absolute;
                top: 5px;
                right: 5px;
                padding: 3px 8px;
                font-size: 10px;
                border-radius: 3px;
                color: white;
            }
            .postgres-source {
                background-color: #336791;
            }
            .wordpress-source {
                background-color: #21759b;
            }
            
            /* Flatsome specific fixes */
            .header-block .custom-search-container,
            .header-block .flex-col .custom-search-container,
            .header-bottom .custom-search-container,
            .header-main .custom-search-container {
                width: 100%;
                max-width: 800px; /* Increased width */
                padding: 0;
                margin: 0 auto;
            }
            
            /* Center column specific styles */
            .flex-col.flex-center .custom-search-container,
            .flex-col.flex-grow .custom-search-container,
            .flex-col.flex-has-center .custom-search-container {
                min-width: 600px; /* Minimum width for center column */
                max-width: 800px; /* Maximum width for center column */
                width: 100%;
                margin: 0 auto;
            }
            
            /* Make sure the search bar doesn't overflow its container */
            .flex-col .custom-search-container {
                width: 100% !important;
                overflow: hidden;
            }
            
            /* Ensure the form fits within its container */
            .flex-col .custom-search-container form {
                width: 100%;
                max-width: 100%;
            }
            
            @media (max-width: 849px) {
                .flex-col.flex-center .custom-search-container,
                .flex-col.flex-grow .custom-search-container,
                .flex-col.flex-has-center .custom-search-container {
                    min-width: 90%;
                    width: 90%;
                }
                
                .custom-search-container {
                    max-width: 90%;
                    width: 90%;
                }
                
                .custom-search-container input[type="text"] {
                    width: 100%;
                    font-size: 14px;
                }
                
                .custom-search-container button {
                    font-size: 14px;
                    padding: 10px 15px;
                }
                
                .product-grid {
                    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
                    gap: 15px;
                }
                
                .product-image {
                    height: 150px;
                }
            }
            
            @media (min-width: 850px) and (max-width: 1100px) {
                .flex-col.flex-center .custom-search-container,
                .flex-col.flex-grow .custom-search-container,
                .flex-col.flex-has-center .custom-search-container {
                    min-width: 500px;
                    max-width: 600px;
                }
            }
        </style>
        <?php
    }
    
    /**
     * Render search form shortcode
     */
    public function render_search_form($atts) {
        $atts = shortcode_atts(array(
            'placeholder' => 'Search for products...',
            'button_text' => 'Search',
            'results_page' => '', // URL of the page with search results shortcode
        ), $atts, 'custom_product_search');
        
        // Get the search term if it exists
        $search_term = isset($_GET['term']) ? sanitize_text_field($_GET['term']) : '';
        
        // Determine form action
        $form_action = !empty($atts['results_page']) ? esc_url($atts['results_page']) : esc_url($_SERVER['REQUEST_URI']);
        
        ob_start();
        ?>
        <div class="custom-search-container">
            <form action="<?php echo $form_action; ?>" method="GET">
                <input type="text" name="term" placeholder="<?php echo esc_attr($atts['placeholder']); ?>" 
                       value="<?php echo esc_attr($search_term); ?>">
                <button type="submit"><?php echo esc_html($atts['button_text']); ?></button>
            </form>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Render search results shortcode
     */
    public function render_search_results($atts) {
        $atts = shortcode_atts(array(
            'limit' => 20,
        ), $atts, 'custom_product_search_results');
        
        // Get the search term
        $search_term = isset($_GET['term']) ? sanitize_text_field($_GET['term']) : '';
        
        // If no search term, show a message instead of the search form
        if (empty($search_term)) {
            ob_start();
            ?>
            <div class="no-results">
                <p>Please use the search bar to find products.</p>
            </div>
            <?php
            return ob_get_clean();
        }
        
        // Database configurations
        $pg_config = array(
            'host'     => 'localhost',
            'port'     => '5432',
            'dbname'   => 'products_db',
            'user'     => 'products_user',
            'password' => 'products_2@'
        );
        
        // WordPress DB config is handled by WordPress itself
        
        $combined_results = array();
        $total_results = 0;
        $errors = array();
        
        ob_start();
        
        try {
            // POSTGRESQL SEARCH
            try {
                if (function_exists('pg_connect')) {
                    // Create database connection string
                    $conn_string = sprintf(
                        "host=%s port=%s dbname=%s user=%s password=%s",
                        $pg_config['host'],
                        $pg_config['port'],
                        $pg_config['dbname'],
                        $pg_config['user'],
                        $pg_config['password']
                    );
                    
                    // Create database connection
                    $pg_conn = pg_connect($conn_string);
                    
                    if (!$pg_conn) {
                        throw new Exception("PostgreSQL Connection failed: " . pg_last_error());
                    }
                    
                    // Prepare query with ILIKE for case-insensitive search
                    $pg_query = "SELECT title, price, product_link, category, image_url 
                                FROM products 
                                WHERE title ILIKE $1 
                                LIMIT " . intval($atts['limit']);
                    
                    // Add wildcards to search term
                    $search_pattern = "%{$search_term}%";
                    
                    // Execute the query
                    $pg_result = pg_query_params($pg_conn, $pg_query, array($search_pattern));
                    
                    if (!$pg_result) {
                        throw new Exception("PostgreSQL query execution failed: " . pg_last_error());
                    }
                    
                    // Process PostgreSQL results
                    while ($product = pg_fetch_assoc($pg_result)) {
                        $product['source'] = 'postgres';
                        $combined_results[] = $product;
                        $total_results++;
                    }
                    
                    // Clean up PostgreSQL resources
                    pg_free_result($pg_result);
                    pg_close($pg_conn);
                } else {
                    $errors[] = "PostgreSQL functions not available. Make sure the PHP PostgreSQL extension is installed.";
                }
            } catch (Exception $e) {
                $errors[] = "PostgreSQL Error: " . $e->getMessage();
            }
            
            // WORDPRESS SEARCH
            try {
                global $wpdb;
                
                // Prepare search pattern for MySQL
                $search_pattern = '%' . $wpdb->esc_like($search_term) . '%';
                
                // Query for WordPress posts
                $wp_posts = $wpdb->get_results(
                    $wpdb->prepare(
                        "SELECT 
                            p.ID, 
                            p.post_title as title, 
                            p.guid as product_link
                        FROM 
                            {$wpdb->posts} p
                        WHERE 
                            p.post_type IN ('post', 'page', 'product') 
                            AND p.post_status = 'publish'
                            AND p.post_title LIKE %s
                        LIMIT %d",
                        $search_pattern,
                        intval($atts['limit'])
                    )
                );
                
                // Process WordPress results
                foreach ($wp_posts as $post) {
                    $product = array(
                        'title' => $post->title,
                        'product_link' => get_permalink($post->ID),
                        'source' => 'wordpress',
                        'category' => '',
                        'price' => '',
                        'image_url' => ''
                    );
                    
                    // Get category
                    $categories = get_the_terms($post->ID, 'category');
                    if (!empty($categories) && !is_wp_error($categories)) {
                        $product['category'] = $categories[0]->name;
                    }
                    
                    // Get product category if it's a WooCommerce product
                    $product_cats = get_the_terms($post->ID, 'product_cat');
                    if (!empty($product_cats) && !is_wp_error($product_cats)) {
                        $product['category'] = $product_cats[0]->name;
                    }
                    
                    // Get price if it's a WooCommerce product
                    if (function_exists('wc_get_product')) {
                        $wc_product = wc_get_product($post->ID);
                        if ($wc_product) {
                            $product['price'] = $wc_product->get_price();
                        }
                    }
                    
                    // Get featured image
                    if (has_post_thumbnail($post->ID)) {
                        $product['image_url'] = get_the_post_thumbnail_url($post->ID, 'medium');
                    }
                    
                    $combined_results[] = $product;
                    $total_results++;
                }
                
            } catch (Exception $e) {
                $errors[] = "WordPress Search Error: " . $e->getMessage();
            }
            
            // Search form is removed from results page since it's already in the header
            
            // Display results if we have any
            if ($total_results > 0) {
                ?>
                <div class="search-results-container">
                    <h3>Search Results for: "<?php echo esc_html($search_term); ?>"</h3>
                    <div class="product-grid">
                    <?php
                    foreach ($combined_results as $product) {
                    ?>
                    <div class="product-item">
                        <?php if ($product['source'] == 'postgres'): ?>
                            <span class="source-badge postgres-source">PG</span>
                        <?php else: ?>
                            <span class="source-badge wordpress-source">WP</span>
                        <?php endif; ?>
                        
                        <div class="product-image">
                        <?php if (!empty($product['image_url'])): ?>
                        <a href="<?php echo esc_url($product['product_link']); ?>" target="_blank">
                        <img src="<?php echo esc_url($product['image_url']); ?>" 
                        alt="<?php echo esc_attr($product['title']); ?>">
                        </a>
                        <?php else: ?>
                        <div class="no-image">No Image Available</div>
                        <?php endif; ?>
                        </div>
                        <div class="product-details">
                        <h4>
                        <a href="<?php echo esc_url($product['product_link']); ?>" target="_blank">
                        <?php echo esc_html($product['title']); ?>
                        </a>
                        </h4>
                        <?php if (isset($product['price']) && !empty($product['price'])): ?>
                        <div class="price">
                        Rs. <?php echo esc_html($product['price']); ?>
                        </div>
                        <?php endif; ?>
                        <?php if (isset($product['category']) && !empty($product['category'])): ?>
                        <div class="category">
                        <?php echo esc_html($product['category']); ?>
                        </div>
                        <?php endif; ?>
                        <a href="<?php echo esc_url($product['product_link']); ?>" 
                        class="view-details" 
                        target="_blank">
                        View Details
                        </a>
                        </div>
                    </div>
                    <?php
                    }
                    ?>
                    </div>
                </div>
                <?php
            } else {
                ?>
                <div class="no-results">
                    <p>No products found matching your search for "<?php echo esc_html($search_term); ?>".</p>
                </div>
                <?php
            }
            
        } catch (Exception $e) {
            ?>
            <div class="error-message">
                <p>An error occurred while searching for products:</p>
                <p><?php echo esc_html($e->getMessage()); ?></p>
                <?php if (!empty($errors)): ?>
                    <ul>
                        <?php foreach($errors as $error): ?>
                            <li><?php echo esc_html($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
            <?php
        }
        
        return ob_get_clean();
    }
}

// Initialize the plugin
new Custom_Product_Search();

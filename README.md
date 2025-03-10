# Custom Product Search for Woocommerce

This plugin adds a custom product search functionality to your WordPress site, allowing you to search both WordPress posts/products and external PostgreSQL database products.

## Installation

1. Upload the `custom-product-search` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Configure the PostgreSQL database connection in the plugin file if needed

## Setup Instructions

### Step 1: Activate the Plugin

1. Go to your WordPress admin dashboard → Plugins
2. Find "Custom Product Search" and click "Activate"

### Step 2: Create a Search Results Page

1. Go to Pages → Add New
2. Title it "Product Search Results" (or any name you prefer)
3. Add this shortcode to the content:
   ```
   [custom_product_search_results]
   ```
4. Publish the page
5. Note the URL of this page (e.g., https://yourdomain.com/product-search-results/)

### Step 3: Add the Search Bar to Your Theme

#### Option 1: Add to a Page or Post

1. Edit any page where you want the search bar
2. Add this shortcode (replace with your actual results page URL):
   ```
   [custom_product_search results_page="https://yourdomain.com/product-search-results/"]
   ```

#### Option 2: Add to Header (Recommended)

Since you're using Flatsome, you can add it to your header:

1. Go to Appearance → Customize → Header → Header Builder
2. Add a new element (Custom HTML)
3. Insert the shortcode:
   ```
   [custom_product_search results_page="https://yourdomain.com/product-search-results/"]
   ```
4. Position it where you want in your header
   - For a centered search bar: Add it to the center column
   - For a full-width search bar: Add it to a full-width row
5. Save your changes

#### Option 3: Add to a Widget Area

1. Go to Appearance → Widgets
2. Add a "Text" widget to your desired widget area
3. Insert the shortcode as shown above

### Step 4: Test the Search Functionality

1. Visit your website
2. Try searching for a product using your new search bar
3. Verify that you're redirected to the search results page
4. Check that the search results display correctly

## Customizing the Appearance

### Basic Color Customization

If you want to match the search bar to your Flatsome theme's colors:

1. Go to Appearance → Customize → Additional CSS
2. Add custom CSS like:
   ```css
   .custom-search-container button {
       background-color: #446084; /* Replace with your theme's primary color */
   }
   
   .custom-search-container input[type="text"]:focus {
       border-color: #446084; /* Replace with your theme's primary color */
   }
   
   .view-details {
       background-color: #446084; /* Replace with your theme's primary color */
   }
   
   .view-details:hover {
       background-color: #364e68; /* Darker shade for hover */
   }
   ```

### Advanced Customization Options

#### Adjusting Search Bar Width

The search bar is configured to be wide (800px max-width) when placed in the center. If you want to adjust this:

```css
.custom-search-container {
    max-width: 1000px; /* Increase or decrease as needed */
}

.flex-col.flex-center .custom-search-container {
    min-width: 700px; /* Adjust minimum width */
}
```

#### Changing Button Style

To modify the search button appearance:

```css
.custom-search-container button {
    background-color: #your-color;
    border-radius: 0 4px 4px 0; /* Adjust corner roundness */
    font-weight: bold; /* Make text bold */
    text-transform: uppercase; /* Make text uppercase */
}
```

#### Styling the Input Field

To customize the search input field:

```css
.custom-search-container input[type="text"] {
    border: 2px solid #ddd; /* Thicker border */
    font-size: 18px; /* Larger text */
    height: 46px; /* Taller input field */
}
```

## Shortcode Parameters

### Search Form Shortcode

```
[custom_product_search]
```

Parameters:
- `placeholder`: Text to display in the search input field (default: "Search for products...")
- `button_text`: Text for the search button (default: "Search")
- `results_page`: URL of the page where search results should be displayed

Example with all parameters:
```
[custom_product_search placeholder="Find products..." button_text="Go" results_page="https://yourdomain.com/search-results"]
```

### Search Results Shortcode

```
[custom_product_search_results]
```

Parameters:
- `limit`: Maximum number of results to display (default: 20)

Example:
```
[custom_product_search_results limit="30"]
```

## Troubleshooting

### Common Issues

1. **Search bar not appearing**: Make sure the plugin is activated and the shortcode is correctly added.

2. **No search results**: Check your database connection settings in the plugin file. You might need to edit the `custom-product-search.php` file to update the PostgreSQL connection details.

3. **Styling issues**: If the search bar doesn't look right, you may need to add custom CSS to override your theme's styles.

4. **Error messages**: If you see database connection errors, verify that your PostgreSQL server is running and accessible from your WordPress server.

5. **Width problems**: If the search bar is too narrow or too wide, adjust the CSS as shown in the Advanced Customization section.

### WordPress Database Table Prefix

The plugin automatically uses WordPress's built-in `$wpdb` global object, which handles the correct table prefix for your WordPress installation. You don't need to modify any settings for different WordPress table prefixes.

### PostgreSQL Connection

The plugin is configured to connect to a PostgreSQL database with these default settings:

```php
$pg_config = array(
    'host'     => 'localhost',
    'port'     => '5432',
    'dbname'   => 'products_db',
    'user'     => 'products_user',
    'password' => 'products_2@'
);
```

If you need to change these settings, edit the `custom-product-search.php` file.

## Using on Multiple WordPress Sites

If you want to use this plugin on multiple WordPress sites, you'll need to:

1. **Upload the plugin to each site**: Copy the plugin folder to the `/wp-content/plugins/` directory on each WordPress installation.

2. **Configure PostgreSQL settings for each site**: If your sites connect to different PostgreSQL databases, edit the `$pg_config` array in the plugin file for each installation.

3. **Create search results pages on each site**: Follow Step 2 of the setup instructions for each WordPress site.

4. **Customize as needed**: Each site may require different styling or configuration based on its theme and layout.

The plugin automatically adapts to different WordPress database table prefixes, so you don't need to worry about that.

## Support

For support or customization requests, please contact the plugin author.

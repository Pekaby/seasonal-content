
# Seasonal Content – Elementor Addon

## Description

**Seasonal Content** is a lightweight (for **end users** (visitors of the site)) Elementor addon that allows you to change content dynamically based on custom seasonal categories with specific dates. Enhance your WordPress site by automatically displaying different text, headings, or background images when a selected date arrives.

## Features

- **Custom Categories** – Create seasonal categories with custom names and dates in the admin panel.
- **Elementor Integration** – Works seamlessly with Elementor, allowing you to modify text, headings, and background images of parent containers.
- **Automated Content Updates** – Once a category's date arrives, the content updates automatically without manual intervention.
- **Minimal Performance Impact** – The plugin makes only two `get_option` database request (autoload enabled) to check if the date has arrived.

## How It Works

1. **Create a Seasonal Category** – Go to the plugin settings and add a new category with a name and start/end dates.
2. **Edit Content in Elementor** – Select a supported Elementor element (text, heading, or parent container background).
3. **Assign a Category** – Choose one of your predefined categories and enter the alternative content.
4. **Save & Wait** – When the specified date arrives, the plugin will automatically update the content.

## Installation
#### WordPress

1. Log in to your WordPress admin panel.
2.   Go to **Plugins > Add New**.
3.   Search for **"Seasonal Content"**.
4.   Click **Install Now** next to the plugin.
5.   After installation, click **Activate**.

#### via Git and Composer
1. Navigate to plugins directory:
	```bash
	cd /your/path/to/wp-content/plugins
	```
 2. Clone the repository:
	 ```bash
	 git clone https://github.com/Pekaby/seasonal-content.git
	 ```

 3. Navigate to the plugin folder and Initialize composer:
	  ```bash
	 cd seasonal-content
	 composer install --no-dev --optimize-autoloader
	 ```
4. Go to **Plugins->Installed plugins** and activate Seasonal Content

## Requirements

- WordPress 5.8+ (Tested up to: 6.7.2)
- Elementor 3.0+
- PHP 7.4+ (Recommended PHP 8.0 +)

## Future Plans

- Support for more Elementor widgets.
- Plugin settings: change the cron schedule for date checks and add different content update processes.
- Paid addons for extended functionality and features.

## License

This plugin is released under the [GNU  General Public License v3.0](LICENSE).

---

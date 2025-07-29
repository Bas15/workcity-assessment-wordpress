# Workcity Client Projects Plugin

A simple WordPress plugin that registers a custom post type called **Client Project** with custom meta fields and provides a shortcode to display the projects on the frontend.

## Features

- Custom Post Type: `Client Project`
- Custom Meta Fields:
  - Title
  - Client Name
  - Description
  - Status
  - Deadline
- Admin UI for managing client projects
- Shortcode `[client_projects]` to display all projects in a list format

## Installation

1. Download or clone this repository.
2. Zip the `workcity-client-projects-plugin` directory if needed.
3. Go to your WordPress admin dashboard.
4. Navigate to **Plugins > Add New > Upload Plugin**.
5. Upload the ZIP file and click **Install Now**.
6. Activate the plugin.

## Usage

To display all client projects on any post or page, use the shortcode:

```plaintext
[client_projects]

# Igor Vuković - Cinematic Portfolio WordPress Theme

A minimal, cinematic WordPress theme designed specifically for **Igor Vuković** (Cinematographer & Director of Photography) to showcase film projects, commercial reels, narrative work, and music videos.

**Live Site**: [igorvukovic.com](https://igorvukovic.com/)

The theme prioritizes visual grandeur, premium minimal typography, responsive performance, and ease of management through the WordPress Admin panel.

---

## 🎬 Key Features

### 1. Dynamic Video Background Splash Screen
- **Cinematic Homepage**: A fullscreen splash screen containing the cinematographer's name, title, and category navigation.
- **Randomized Background Video**: The background automatically queries a random project from the portfolio that has a valid Vimeo/YouTube URL.
- **Autoplay & Muted**: Plays seamlessly in the background with a cinematic blurred/fade transition to mask buffering times.
- **Fallbacks**: Uses the project's featured image or retrieves the high-resolution thumbnail from the video provider API (Vimeo/YouTube) as a background poster before the video loads.

### 2. Custom Project Post Types & Sorting
- **`project` Custom Post Type (CPT)**: Dedicated admin section for managing individual video projects.
- **`project_category` Custom Taxonomy**: Organizes projects (e.g., Commercial, Narrative, Music Video) with built-in admin terms manager.
- **Drag-and-Drop Credits Builder**: Custom admin metabox that lets you dynamically add, remove, and reorder credits (Role / Name) via a drag-and-drop sortable interface.
- **Project Order**: Custom meta key `_project_order` to determine the specific display order in the portfolio grid.

### 3. AJAX-Powered Portfolio Grid
- **Category Filter Navigation**: Smoothly filters projects without reloading the page.
- **AJAX Loading**: Fetches updated project grids dynamically.
- **Video Lightbox**: Click on a project in the grid to display its video overlay inside a sleek, immersive modal (supporting autoplay, close buttons, and swipe-friendly sizing).

### 4. Custom Single Project Template
- Supports high-quality Vimeo/YouTube embeds that scale correctly across desktop and mobile screens.
- Displays the sorted, dynamic credits list below the video block with modern typography.

### 5. Custom Contact Template
- Dedicated contact page template (`templates/template-contact.php`).
- Manage contact details directly from the page editor:
  - Name, Role/Title, Email, Phone, Instagram.
  - Profile Photo uploader using the native WordPress Media Library.
  - Optional Agency information (Agency Name, Agency Email, Agency Phone).

### 6. Responsive Breakpoints Cheat Sheet
The layout adjusts gracefully to all screen dimensions:
- **`1920px+` (Large Monitor)**: Large grid layouts capped with proper padding for wider screens.
- **`1440px` / `1366px` / `1280px` (Laptops)**: Custom container margins, proportional typography scaling, and fluid grid sizing.
- **`1024px` (Small Laptop)**: Margins tighten, credits adapt.
- **`978px` (Tablet - Landscape)**: **Hamburger menu activates** to replace desktop navigation; portfolio grid changes to 2 columns, and contact layouts stack.
- **`768px` (Tablet - Portrait)**: Lightbox and header adapt, and single project metadata stacks.
- **`500px - 0px` (Mobile)**: Single column portfolio layouts, optimized paddings, and smaller font headings for maximum readability.

---

## 📂 Directory & File Structure

```text
igor-vuckovic/
├── style.css                 # Main stylesheet containing theme metadata and responsive breakpoints
├── functions.php             # Core theme setup, CPTs, taxonomies, admin metaboxes, AJAX actions, and helper APIs
├── front-page.php            # Cinematic fullscreen homepage (splash screen) with background video
├── index.php                 # Archive / Portfolio Grid with category filters
├── single-project.php        # Detail page for individual projects with embedded video and dynamic credits
├── taxonomy-project_category.php # WordPress fallback for category-specific pages
├── page.php                  # Default page template
├── 404.php                   # 404 page template
├── footer.php                # Site footer containing script triggers and mobile menu drawers
├── header.php                # Site header, primary desktop nav, and hamburger button
├── templates/
│   ├── template-contact.php  # Contact Page template displaying profile information and agency details
│   └── template-home.php     # Custom home page template wrapper
└── js/
    └── main.js               # AJAX queries, category navigation, hamburger toggling, and lightbox modal logic
```

---

## 🛠️ Theme Setup & Installation

1. **Upload Theme**: Compress the theme folder into `igor-vuckovic.zip` and upload it via **WordPress Admin > Appearance > Themes > Add New > Upload Theme**, or place it directly into the `/wp-content/themes/` directory of your WordPress installation.
2. **Activate Theme**: Activate the theme in the WordPress dashboard. Upon activation:
   - The **Projects** CPT and **Categories** taxonomy are registered automatically.
   - Rewrite rules are automatically flushed.
3. **Setup Navigation Menu**:
   - Go to **Appearance > Menus** and create a menu.
   - Assign the menu to the **Primary Menu** theme location.
4. **Setup Homepage**:
   - Create a page (e.g., "Home").
   - Under Page Attributes, select the **Default Template** or **Home Page Template**.
   - Go to **Settings > Reading**, set **Your homepage displays** to **A static page**, and select your newly created home page.
5. **Setup Contact Page**:
   - Create a page (e.g., "Contact").
   - Under Page Attributes, select **Contact Template** (located in `templates/template-contact.php`).
   - Fill in the Contact details in the metabox panel at the bottom of the page editor and publish it.

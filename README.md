# Smart FAQ Accordion

Smart FAQ Accordion is a lightweight, production-ready WordPress plugin that lets you add **SEO-optimized FAQ accordions** to blog posts with ease.  
It is built with an **engineering-first approach**, focusing on clean architecture, performance, and search visibility.

---

## 🚀 Features

- ✅ Add FAQs directly to **WordPress posts**
- ✅ Works with **Classic Editor** (Gutenberg compatible)
- ✅ Display FAQs using:
  - Shortcode: `[smart_faq]`
  - Auto-append at the end of posts
- ✅ Interactive **accordion UI**
  - Single open item
  - First item open by default
- ✅ Built-in **FAQPage JSON-LD schema**
- ✅ SEO & AEO friendly (Google rich results ready)
- ✅ Clean **Settings page** for full control
- ✅ Performance-optimized (conditional asset loading)
- ✅ Secure (nonces, sanitization, defensive checks)
- ✅ Avoids duplication when shortcode + auto-append are both used

---

## 📦 Installation

### Option 1: Manual Upload
1. Download or clone this repository
2. Zip the `smart-faq-accordion` folder
3. Upload via **WordPress Admin → Plugins → Add New → Upload Plugin**
4. Activate the plugin

### Option 2: Local Development
```bash
git clone https://github.com/your-username/smart-faq-accordion.git
Place the folder inside:
wp-content/plugins/
```

🧩 Usage
Add FAQs to a Post

Edit any Post

Use the Smart FAQ meta box

Add your questions and answers

Save the post

Display FAQs

You can choose one of the following:

1. Auto-Append

Enable auto-append from:
Settings → Smart FAQ Accordion

FAQs will appear automatically at the end of posts.

2. Shortcode

Use this shortcode anywhere inside post content:
[smart_faq]

If the shortcode is present, auto-append is automatically disabled for that post to prevent duplication.

⚙️ Settings Overview

Navigate to:
Settings → Smart FAQ Accordion

Available options:

Display mode (Shortcode only / Auto-append)

Accordion behavior (single open, first open)

Theme selection

Icon style (Chevron / Plus / None)

🔍 SEO & Schema

Automatically outputs FAQPage JSON-LD schema

Injected via wp_head

Only outputs schema when FAQs exist

Safe to use alongside SEO plugins like:

Yoast SEO

Rank Math

All in One SEO

The plugin handles its own FAQ schema independently.

🛡️ Security & Performance

Nonce validation on save

Sanitized and escaped output

Assets loaded only when FAQs exist

No global CSS or JS pollution

Namespaced functions to avoid conflicts

🧠 Roadmap (Planned)

🤖 AI-powered FAQ generation from post content

🧱 Gutenberg / FSE block support

🎨 Additional themes and animations

📊 FAQ analytics & impressions tracking

👨‍💻 Author

Sheikh Muhammad Ayyan Tariq
Full-Stack WordPress Developer
LinkedIn: https://www.linkedin.com/in/ayyantariq/

📄 License

This project is licensed under the GPL-2.0+ License
Feel free to use, modify, and distribute.

⭐ Contribute

If you find this plugin useful:

Star the repository ⭐

Open issues for suggestions or bugs

Fork and submit pull requests

Happy building 🚀

# Technical Documentation: Discover Isabela Tourism Website

**Course**: IT AppDev 2 – Web Applications  
**Activity**: Website Development Project – Media Content Integration  
**Aligned CLO**: CLO1 (Weeks 2–3)  

---

## 1. Project Title
**"Discover Isabela: A Responsive Tourism and Heritage Website"**

---

## 2. Project Description
- **Purpose**: To provide a fully functional, responsive, accessible, and media-rich web application introducing the eco-tourism spots, cultural heritage landmarks, and travel activities of Isabela Province, Philippines.
- **Target Users**: Local and international travelers, heritage researchers, students, and eco-tourism adventure seekers looking for verified destination guides and media asset previews.
- **Main Features**:
  1. Responsive `<picture>` media hero banner adapting seamlessly across mobile, tablet, and desktop displays.
  2. Integrated HTML5 `<audio>` tour guide narration with collapsible text transcripts.
  3. Integrated HTML5 `<video>` promotional presentation featuring subtitle captioning (`.vtt`) and transcript drawers.
  4. Interactive Media Management Gallery (`gallery.php`) supporting category filters, media metadata display, and user file deletion.
  5. Validated File Upload Portal (`upload.php`) with client-side drag-and-drop and server-side PHP MIME & size validation.
  6. Accessibility compliance following WCAG 2.1 AA guidelines.

---

## 3. Media Inventory

| Media Category | Filename / Identifier | Format | Dimensions / Duration | Description |
| :--- | :--- | :--- | :--- | :--- |
| **Image** | `banner-desktop.webp` | WebP | 1200x500 px | Hero banner optimized for desktop viewports (>1024px) |
| **Image** | `banner-tablet.webp` | WebP | 800x400 px | Hero banner optimized for tablet viewports (601-1024px) |
| **Image** | `banner-mobile.webp` | WebP | 450x300 px | Hero banner optimized for smartphone viewports (<=600px) |
| **Image** | `ilagan-sanctuary.jpg` | JPEG | 800x500 px | Eco-park and Japanese tunnel destination photo |
| **Image** | `dibulo-falls.jpg` | JPEG | 800x500 px | Dibulo waterfalls photo in Dinapigue Isabela |
| **Image** | `abuan-river.jpg` | JPEG | 800x500 px | Abuan river kayaking eco-adventure photo |
| **Audio** | `heritage-tour-guide.mp3` | MP3 | 00:04 sec | Voice narration introduction to Isabela culture |
| **Audio** | `isabela-environmental-nature.wav` | WAV | 00:03 sec | Environmental sound stream |
| **Video** | `isabela-promo.mp4` | MP4 | 00:05 sec | Official promotional video with subtitles track |
| **Video Captions** | `subtitles.vtt` | WebVTT | N/A | Closed captions file for the video presentation |

---

## 4. Media Format Justification

| Media Type | Format Used | Purpose | Reason for Selection |
| :--- | :--- | :--- | :--- |
| **Image** | WebP / JPEG / PNG | Page banners, destination cards, modal previews | **WebP** offers superior lossy/lossless compression with small byte size for hero `<picture>` switching. **JPEG** provides high color fidelity for photography, while **PNG** retains sharp vector/alpha elements. |
| **Audio** | MP3 / WAV / OGG | Audio guide, voice introduction, nature soundscapes | **MP3** and **WAV** are universally supported across modern desktop and mobile browsers via HTML5 `<audio>`, providing immediate playback without plugins. |
| **Video** | MP4 (H.264 / AAC) / WebM | Travel promo presentation & drone tour showcase | **MP4** with H.264 video codec offers native hardware decoding across iOS, Android, Windows, and macOS browsers, supporting standard WebVTT closed captions. |

---

## 5. Responsive Design Implementation
The application adheres to mobile-first responsive web design principles using CSS flexbox, CSS grid, media queries, and fluid media constraints:

- **Desktop View (>1024px)**: Full multi-column grid layout, wide hero banner (`banner-desktop.webp`), horizontal navigation header, and multi-card media gallery.
- **Tablet View (601px - 1024px)**: 2-column adaptive grid layout, tablet-optimized media asset (`banner-tablet.webp`), scaled form controls.
- **Smartphone View (<=600px)**: 1-column stacked layout, mobile hamburger menu navigation toggle, mobile media asset (`banner-mobile.webp`), 100% width button controls.
- **Fluid Media Sizing**: Enforced via global CSS rule:
  ```css
  img, video {
      max-width: 100%;
      height: auto;
  }
  audio {
      width: 100%;
  }
  ```

---

## 6. Accessibility Implementation (WCAG 2.1 AA)
1. **Meaningful Alternative Text**: All images possess context-rich `alt` attributes describing the scene (e.g. `alt="Dibulo Falls in Dinapigue Isabela with cascading natural waters"`).
2. **Accessible Transcripts & Subtitles**: HTML5 `<audio>` and `<video>` tags are paired with collapsible transcript panels and WebVTT caption tracks (`subtitles.vtt`).
3. **Form Labels & Controls**: Every `<input>` and `<select>` element is explicitly paired with a `<label for="...">`.
4. **Keyboard Focus & Contrast**: `:focus-visible` ring indicators (3px cyan outline) and high-contrast color choices guarantee readable content for low-vision and keyboard-only users.
5. **No Autoplay**: Media elements require explicit user action to start audio playback.

---

## 7. File Upload and Media Management
- **File Upload Process (`upload.php`)**:
  1. Users select a file or drag-and-drop into the custom drop zone.
  2. PHP validates file size (`<= 20MB`) and MIME type (`image/jpeg`, `image/png`, `image/webp`, `audio/mpeg`, `audio/wav`, `video/mp4`, etc.).
  3. Filenames are sanitized into descriptive slugs (e.g., `isabela-tumauini-church-1723700000.jpg`).
  4. Validated files are saved to categorized upload paths (`/uploads/images/`, `/uploads/audio/`, `/uploads/videos/`).
- **Media Management (`gallery.php`)**:
  - Displays both built-in and user-uploaded media with interactive category filter tabs (*All*, *Images*, *Audio*, *Videos*).
  - Provides a secure file deletion form restricting deletion targets strictly to the `/uploads/` directory via `realpath()` checks.

---

## 8. Testing Results
- **HTML5 & CSS Validation**: Validated with zero missing closing tags or missing alt attributes.
- **Cross-Browser Verification**: Playback verified in Google Chrome, Microsoft Edge, Mozilla Firefox, and Mobile Safari emulation.
- **Responsive Viewport Simulation**: Verified at 1440px (Desktop), 768px (Tablet), and 375px (Mobile).
- **Upload & Security Validation**: Tested with valid `.jpg`, `.mp3`, `.mp4` uploads (succeeded) and invalid `.exe` or oversized files (rejected with proper error alert).

---

## 9. Problems Encountered and Solutions

1. **Problem**: Audio and video players overflowing beyond container boundaries on small smartphone viewports.  
   **Solution**: Applied responsive media wrappers in CSS with `width: 100%` and `max-width: 100%`, combined with `overflow: hidden` on parent containers.

2. **Problem**: Security vulnerability where malicious users could attempt directory traversal during media file deletion (e.g. `../../index.php`).  
   **Solution**: Implemented strict PHP server-side validation using `realpath()` to ensure deletion requests only operate within the `uploads/` directory tree.

3. **Problem**: Images swapping on mobile viewports causing layout reflow and slow loading.  
   **Solution**: Implemented the HTML5 `<picture>` tag with `srcset` and `media` attributes, allowing the browser engine to fetch only the appropriate image asset before rendering.

---

## 10. Conclusion
This activity successfully demonstrated how to integrate, format, optimize, and serve diverse media content (images, audio, video) in a functional web application. Through modern responsive media techniques, robust PHP upload validation, and accessible design principles, the resulting **Discover Isabela Tourism Website** satisfies all CLO1 requirements.

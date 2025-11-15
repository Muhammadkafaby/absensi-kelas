# Enterprise UI Design System

Dokumentasi untuk tampilan enterprise-grade yang baru untuk Sistem Absensi SMA NU Kaplongan.

## 🎨 Fitur Enterprise UI

### 1. Enhanced Design System
- **Color Palette**: Sophisticated green-based color system dengan 10 tingkat gradasi
- **Typography Scale**: 9 ukuran font profesional dengan Inter font family
- **Spacing System**: Konsisten spacing dari 4px hingga 80px
- **Advanced Shadows**: 6 level shadow dengan green-tinted effects
- **Glassmorphism**: Modern frosted glass effects dengan blur backgrounds

### 2. Modern Sidebar Navigation
- **Collapsible Sidebar**: Dapat di-collapse untuk menghemat ruang layar
- **Sticky Navigation**: Tetap terlihat saat scroll
- **Active State Indicators**: Visual feedback untuk halaman aktif
- **Icon-based Menu**: Icons yang mudah dikenali
- **User Profile Section**: Informasi user di sidebar footer
- **Responsive**: Otomatis menjadi slide-in menu di mobile

### 3. Advanced Dashboard Components
- **Metric Cards**: Card statistik dengan hover effects dan animations
- **Interactive Charts**: Chart.js dengan styling enterprise yang konsisten
- **Enterprise Table**: Advanced table dengan search, hover effects, dan responsive design
- **Empty States**: Tampilan menarik saat tidak ada data
- **Loading States**: Skeleton screens untuk loading yang lebih baik
- **Quick Actions**: Shortcut cards untuk akses cepat ke fitur penting

### 4. Dark Mode Support
- **Toggle Theme**: Switch antara light dan dark mode
- **Persistent Preference**: Preferensi tersimpan di localStorage
- **Smooth Transition**: Transisi yang halus antar theme
- **Optimized Colors**: Color palette yang dioptimasi untuk kedua mode

### 5. Enterprise Features
- **Search Functionality**: Real-time search di table
- **Toast Notifications**: Modern notification system
- **Modal System**: Advanced modal dengan backdrop blur
- **Dropdown Menus**: Context menus dengan smooth animations
- **Badges & Status**: Semantic color-coded badges
- **Progress Indicators**: Visual progress bars
- **Tooltips**: Helpful tooltips untuk UX yang lebih baik

## 📁 File Structure

```
public/assets/
├── css/
│   ├── enterprise-theme.css       # Design system variables & layout
│   ├── enterprise-components.css  # UI components
│   └── toast.css                  # Toast notification styles
├── js/
│   ├── enterprise.js              # Enterprise UI interactions
│   └── toast.js                   # Toast notification system

app/Views/
├── layouts/
│   └── enterprise.php             # Enterprise layout dengan sidebar
├── dashboard/
│   ├── enterprise_admin.php       # Dashboard admin enterprise
│   └── enterprise_guru.php        # Dashboard guru enterprise
```

## 🎯 Design System

### Color Palette

**Primary Green Palette:**
- 50: #ecfdf5 (Lightest)
- 100: #d1fae5
- 200: #a7f3d0
- 300: #6ee7b7
- 400: #34d399
- 500: #10b981 (Primary)
- 600: #059669
- 700: #047857
- 800: #065f46
- 900: #064e3b (Darkest)

**Semantic Colors:**
- Success: #10b981
- Warning: #f59e0b
- Error: #ef4444
- Info: #3b82f6

### Typography

- **Font Family**: Inter (Google Fonts)
- **Font Weights**: 300, 400, 500, 600, 700
- **Font Sizes**: xs (12px) hingga 5xl (48px)
- **Line Heights**: tight (1.25), normal (1.5), relaxed (1.75)

### Spacing

Menggunakan sistem spacing yang konsisten:
- space-1: 4px
- space-2: 8px
- space-3: 12px
- space-4: 16px
- space-6: 24px
- space-8: 32px
- space-12: 48px
- space-16: 64px

### Border Radius

- sm: 4px
- base: 8px
- md: 12px
- lg: 16px
- xl: 24px
- 2xl: 32px
- full: 9999px (circle)

## 🚀 Komponen Utama

### Metric Card
```html
<div class="metric-card">
    <div class="metric-icon">📊</div>
    <div class="metric-label">Label</div>
    <div class="metric-value">100</div>
    <div class="metric-trend positive">
        <span>▲</span>
        <span>Trending Up</span>
    </div>
</div>
```

### Enterprise Card
```html
<div class="enterprise-card">
    <div class="card-header">
        <h2 class="card-title">Card Title</h2>
        <p class="card-subtitle">Subtitle</p>
    </div>
    <!-- Card content -->
</div>
```

### Enterprise Table
```html
<div class="enterprise-table-wrapper">
    <div class="table-toolbar">
        <div class="table-search">
            <span class="table-search-icon">🔍</span>
            <input type="text" class="table-search-input" placeholder="Search...">
        </div>
    </div>
    <table class="enterprise-table">
        <!-- Table content -->
    </table>
</div>
```

### Buttons
```html
<button class="btn-enterprise btn-primary">Primary Button</button>
<button class="btn-enterprise btn-secondary">Secondary Button</button>
<button class="btn-enterprise btn-ghost">Ghost Button</button>

<!-- Sizes -->
<button class="btn-enterprise btn-primary btn-sm">Small</button>
<button class="btn-enterprise btn-primary btn-lg">Large</button>
```

### Badges
```html
<span class="enterprise-badge badge-success">Success</span>
<span class="enterprise-badge badge-warning">Warning</span>
<span class="enterprise-badge badge-error">Error</span>
<span class="enterprise-badge badge-info">Info</span>
```

## 📱 Responsive Design

- **Desktop (>768px)**: Full sidebar dengan collapsible feature
- **Mobile (≤768px)**: Slide-in sidebar dengan mobile toggle button
- **Adaptive Grid**: Components menggunakan auto-fit grid yang responsive
- **Touch-Friendly**: Padding dan target size yang optimal untuk mobile

## ⚡ Performance

- **CSS Variables**: Memudahkan theming dan konsistensi
- **Minimal JavaScript**: Hanya untuk interaktivitas esensial
- **No External Dependencies**: Kecuali Google Fonts dan Chart.js
- **Optimized Animations**: Menggunakan transform dan opacity untuk performa terbaik
- **Lazy Loading**: Chart dan components dimuat saat diperlukan

## 🎨 Customization

### Mengubah Primary Color

Edit di `enterprise-theme.css`:
```css
:root {
    --enterprise-primary-500: #your-color;
    --enterprise-primary-600: #your-darker-color;
    /* ... adjust other shades */
}
```

### Mengubah Sidebar Width

Edit di `enterprise-theme.css`:
```css
:root {
    --sidebar-width: 280px;
    --sidebar-collapsed-width: 80px;
}
```

### Menambah Custom Component

Tambahkan di `enterprise-components.css` dan ikuti naming convention yang ada.

## 🔧 JavaScript API

### Sidebar
```javascript
window.enterpriseUI.toggleSidebar()
window.enterpriseUI.closeSidebar()
```

### Theme
```javascript
window.enterpriseUI.toggleTheme()
```

### Modal
```javascript
window.enterpriseUI.openModal('modal-id')
window.enterpriseUI.closeModal('modal-id')
```

### Utility Functions
```javascript
EnterpriseUtils.formatNumber(1234567)  // "1.234.567"
EnterpriseUtils.formatCurrency(50000)  // "Rp50.000"
EnterpriseUtils.formatDate(new Date()) // "15 November 2025"
EnterpriseUtils.showLoading(container)
EnterpriseUtils.hideLoading(overlay)
```

## 📝 Usage Notes

1. **Gunakan Layout Enterprise**: View harus extend `layouts/enterprise`
2. **Include Extra JS/CSS**: Gunakan section `extra_js` dan `extra_css` jika perlu
3. **Consistent Naming**: Ikuti konvensi penamaan class yang ada
4. **Semantic HTML**: Gunakan HTML5 semantic tags
5. **Accessibility**: Perhatikan ARIA labels dan keyboard navigation

## 🌟 Best Practices

- Gunakan CSS variables untuk konsistensi warna
- Leverage utility classes untuk spacing dan layout
- Ikuti responsive design patterns yang sudah ada
- Test di berbagai ukuran layar
- Perhatikan performance dengan DevTools

## 📚 Resources

- **Font**: [Inter on Google Fonts](https://fonts.google.com/specimen/Inter)
- **Charts**: [Chart.js Documentation](https://www.chartjs.org/docs/)
- **Icons**: Menggunakan Unicode emoji untuk simplicity

## 🔄 Updates & Changelog

### Version 1.0.0 (November 15, 2025)
- ✨ Initial enterprise UI implementation
- 🎨 Enhanced design system dengan green theme
- 📱 Responsive sidebar navigation
- 🌓 Dark mode support
- 📊 Advanced dashboard components
- 🔍 Real-time table search
- 🎯 Empty states dan loading states
- 🚀 Performance optimizations

---

**Developed with ❤️ for SMA NU Kaplongan**

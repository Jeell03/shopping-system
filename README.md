# ShopEasy - E-commerce Website

A complete e-commerce website built with PHP, MySQL, HTML, CSS, and JavaScript.

## Features

### 🛍️ **Core E-commerce Features**
- **Product Catalog**: Browse products by category with filtering and sorting
- **Product Details**: Detailed product pages with images, reviews, and specifications
- **Shopping Cart**: Add/remove items, update quantities, persistent cart
- **User Authentication**: Registration, login, and user profiles
- **Checkout Process**: Complete order placement with address and payment info
- **Order Management**: Order confirmation and tracking

### 🎨 **User Experience**
- **Responsive Design**: Mobile-friendly interface
- **Modern UI**: Clean, professional design with smooth animations
- **Search Functionality**: Find products quickly
- **Product Reviews**: Customer rating and review system
- **Wishlist**: Save favorite products for later

### 🔧 **Admin Features**
- **Admin Dashboard**: Overview of store statistics
- **Product Management**: Add, edit, and manage products
- **Order Management**: View and process orders
- **User Management**: Manage customer accounts
- **Category Management**: Organize products by categories

### 🛡️ **Security & Performance**
- **Secure Authentication**: Password hashing and session management
- **SQL Injection Protection**: Prepared statements
- **XSS Protection**: Input sanitization
- **Responsive Design**: Mobile-optimized interface

## Installation

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)
- XAMPP/WAMP/MAMP (for local development)

### Setup Instructions

1. **Clone/Download the project**
   ```bash
   git clone [repository-url]
   # or download and extract the ZIP file
   ```

2. **Database Setup**
   - Create a MySQL database named `shopeasy`
   - Import the database schema:
   ```bash
   mysql -u root -p shopeasy < database/schema.sql
   ```

3. **Configure Database Connection**
   - Edit `config/database.php` with your database credentials:
   ```php
   $host = 'localhost';
   $dbname = 'shopeasy';
   $username = 'root';
   $password = 'your_password';
   ```

4. **Set Up Web Server**
   - Place the project in your web server's document root
   - For XAMPP: `C:\xampp\htdocs\sampleeccomerce`
   - For WAMP: `C:\wamp64\www\sampleeccomerce`
   - For MAMP: `/Applications/MAMP/htdocs/sampleeccomerce`

5. **Access the Website**
   - Open your browser and navigate to:
   - `http://localhost/sampleeccomerce` (XAMPP/WAMP)
   - `http://localhost:8888/sampleeccomerce` (MAMP)

## Default Admin Access

- **Admin Panel**: `http://localhost/sampleeccomerce/admin`
- **Username**: `admin`
- **Password**: `admin123`

## Project Structure

```
sampleeccomerce/
├── assets/
│   ├── css/
│   │   └── style.css          # Main stylesheet
│   ├── js/
│   │   └── script.js          # JavaScript functionality
│   └── images/                # Product images and assets
├── config/
│   └── database.php           # Database configuration
├── includes/
│   └── functions.php          # Helper functions
├── ajax/                      # AJAX endpoints
├── admin/                     # Admin panel
├── database/
│   └── schema.sql             # Database schema
├── index.php                  # Homepage
├── products.php               # Product catalog
├── product.php                # Individual product page
├── cart.php                   # Shopping cart
├── checkout.php               # Checkout process
├── login.php                  # User login
├── register.php               # User registration
├── search.php                 # Search results
└── README.md                  # This file
```

## Key Features Explained

### 🛒 **Shopping Cart**
- Session-based cart storage
- AJAX-powered add/remove functionality
- Quantity updates without page refresh
- Persistent cart across browser sessions

### 👤 **User System**
- Secure registration and login
- Password hashing with PHP's `password_hash()`
- Session management
- User profile management

### 📦 **Product Management**
- Product catalog with categories
- Image gallery support
- Stock management
- Featured products
- Sale pricing

### 🛍️ **Order Processing**
- Complete checkout flow
- Order confirmation
- Order tracking
- Email notifications (ready for implementation)

### 🎨 **Frontend Features**
- Responsive design
- Modern UI with CSS Grid and Flexbox
- Smooth animations and transitions
- Mobile-first approach

## Customization

### Adding New Products
1. Access the admin panel
2. Navigate to "Manage Products"
3. Add product details, images, and pricing
4. Set categories and stock levels

### Styling Customization
- Edit `assets/css/style.css` for design changes
- Modify color scheme in CSS variables
- Add custom fonts in the HTML head

### Database Modifications
- Update `database/schema.sql` for schema changes
- Modify `includes/functions.php` for new functionality
- Add new tables as needed

## Security Considerations

- All user inputs are sanitized
- SQL queries use prepared statements
- Passwords are hashed using PHP's built-in functions
- Session management is secure
- XSS protection implemented

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## License

This project is open source and available under the [MIT License](LICENSE).

## Support

For support and questions:
- Create an issue in the repository
- Check the documentation
- Review the code comments

## Future Enhancements

- [ ] Payment gateway integration
- [ ] Email notifications
- [ ] Advanced search filters
- [ ] Product variants (size, color)
- [ ] Multi-language support
- [ ] API endpoints
- [ ] Mobile app integration
- [ ] Advanced analytics
- [ ] Inventory management
- [ ] Coupon system

---

**ShopEasy** - Your complete e-commerce solution! 🛍️





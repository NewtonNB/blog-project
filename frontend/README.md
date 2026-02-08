# Blog Frontend - React TypeScript Application

A modern, responsive React frontend for the Laravel blog API backend.

## 🚀 Features

### ✅ Complete Blog Functionality
- **Home Page**: Browse all posts with search and category filtering
- **Post Details**: Read full posts with author information
- **Category Pages**: View posts by category
- **User Authentication**: Login/Register with JWT tokens
- **Dashboard**: Manage your posts (authenticated users)
- **Create/Edit Posts**: Full CRUD operations for posts
- **Responsive Design**: Mobile-first design with Tailwind CSS

### ✅ Technical Features
- **TypeScript**: Full type safety and IntelliSense
- **React Router**: Client-side routing with protected routes
- **Context API**: Global state management for authentication
- **Axios**: HTTP client with interceptors for API calls
- **Tailwind CSS**: Utility-first CSS framework
- **Component Architecture**: Reusable, modular components

## 📁 Project Structure

```
blog-frontend/
├── public/
│   └── index.html                 # HTML template
├── src/
│   ├── components/               # Reusable components
│   │   ├── Navbar.tsx           # Navigation bar
│   │   ├── PostCard.tsx         # Post preview card
│   │   ├── LoadingSpinner.tsx   # Loading indicator
│   │   └── ProtectedRoute.tsx   # Route protection
│   ├── contexts/
│   │   └── AuthContext.tsx      # Authentication context
│   ├── pages/                   # Page components
│   │   ├── Home.tsx            # Homepage with posts
│   │   ├── PostDetail.tsx      # Single post view
│   │   ├── CategoryPosts.tsx   # Category posts view
│   │   ├── Login.tsx           # Login form
│   │   ├── Register.tsx        # Registration form
│   │   ├── Dashboard.tsx       # User dashboard
│   │   ├── CreatePost.tsx      # Create new post
│   │   └── EditPost.tsx        # Edit existing post
│   ├── services/
│   │   └── api.ts              # API service layer
│   ├── App.tsx                 # Main app component
│   ├── index.tsx              # App entry point
│   └── index.css              # Global styles
├── package.json               # Dependencies and scripts
├── tailwind.config.js        # Tailwind configuration
├── postcss.config.js         # PostCSS configuration
└── tsconfig.json             # TypeScript configuration
```

## 🛠 Setup Instructions

### 1. Install Dependencies
```bash
cd blog-frontend
npm install
```

### 2. Start Development Server
```bash
npm start
```

The app will be available at `http://localhost:3000`

### 3. Build for Production
```bash
npm run build
```

## 🔗 API Integration

### Backend Requirements
- Laravel backend running on `http://localhost:8000`
- API endpoints available at `/api/*`
- CORS configured for `localhost:3000`

### Authentication Flow
1. User registers/logs in via API
2. JWT token stored in localStorage
3. Token included in API requests via Axios interceptors
4. Automatic logout on token expiration

### API Endpoints Used
- `POST /api/auth/register` - User registration
- `POST /api/auth/login` - User login
- `POST /api/auth/logout` - User logout
- `GET /api/auth/profile` - Get user profile
- `GET /api/posts` - Get all posts (with filters)
- `GET /api/posts/{slug}` - Get single post
- `POST /api/posts` - Create new post
- `PUT /api/posts/{slug}` - Update post
- `DELETE /api/posts/{slug}` - Delete post
- `GET /api/categories` - Get all categories
- `GET /api/categories/{slug}` - Get category with posts

## 🎨 UI/UX Features

### Design System
- **Colors**: Primary blue theme with gray accents
- **Typography**: System font stack for optimal readability
- **Spacing**: Consistent spacing using Tailwind utilities
- **Components**: Card-based layout with hover effects

### Responsive Design
- **Mobile First**: Optimized for mobile devices
- **Breakpoints**: Responsive grid layouts
- **Navigation**: Collapsible mobile menu
- **Forms**: Touch-friendly form controls

### User Experience
- **Loading States**: Spinners and skeleton screens
- **Error Handling**: User-friendly error messages
- **Form Validation**: Client-side validation with feedback
- **Navigation**: Breadcrumbs and clear navigation paths

## 🔐 Authentication Features

### Public Routes
- Home page with all published posts
- Individual post pages
- Category pages
- Login/Register pages

### Protected Routes
- User dashboard
- Create new post
- Edit existing posts
- Delete posts (own posts only)

### Security
- JWT token authentication
- Automatic token refresh handling
- Protected route components
- User authorization checks

## 📱 Pages Overview

### Home Page (`/`)
- Grid of blog posts with featured images
- Search functionality
- Category filtering
- Pagination
- Responsive design

### Post Detail (`/post/{slug}`)
- Full post content with formatting
- Author information and bio
- Category and publication date
- Edit/Delete buttons (for post owners)
- Related post suggestions

### Dashboard (`/dashboard`)
- User's post management interface
- Post statistics (total, published, drafts)
- Filter posts by status
- Quick actions (view, edit, delete)
- Create new post button

### Create/Edit Post (`/create-post`, `/edit-post/{slug}`)
- Rich form for post creation/editing
- Category selection
- Status management (draft/published)
- Featured image URL input
- Content textarea with preview

### Authentication (`/login`, `/register`)
- Clean, centered forms
- Form validation
- Error handling
- Demo credentials display
- Redirect after successful auth

## 🚀 Getting Started

1. **Start Laravel Backend**:
   ```bash
   cd blog-api
   php artisan serve
   ```

2. **Start React Frontend**:
   ```bash
   cd blog-frontend
   npm install
   npm start
   ```

3. **Test the Application**:
   - Visit `http://localhost:3000`
   - Register a new account or login with demo credentials
   - Create and manage blog posts
   - Browse posts by category

## 🔧 Development

### Available Scripts
- `npm start` - Start development server
- `npm run build` - Build for production
- `npm test` - Run tests
- `npm run eject` - Eject from Create React App

### Environment Variables
Create `.env` file for custom configuration:
```
REACT_APP_API_URL=http://localhost:8000/api
```

## 🎯 Features Implemented

✅ **Complete Authentication System**
✅ **Full CRUD Operations for Posts**
✅ **Category Management**
✅ **Responsive Design**
✅ **Search and Filtering**
✅ **User Dashboard**
✅ **Protected Routes**
✅ **Error Handling**
✅ **Loading States**
✅ **TypeScript Support**

## 🚀 Ready to Use!

Your React frontend is now complete and ready to work with your Laravel backend. The application provides a full-featured blog experience with modern UI/UX patterns and robust functionality.

**Happy blogging! 📝**
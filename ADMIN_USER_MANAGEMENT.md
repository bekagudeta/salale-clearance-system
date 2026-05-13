# Admin User Management Guide

## Overview
Admins can create, edit, and manage all users in the system through the Admin Dashboard. This includes students, department officers, registrars, and other roles.

---

## Creating a New User

### Step 1: Access User Management
1. Login as Super Admin
2. Go to **Admin Dashboard** → **User Management**
3. Click the **"Add User"** button (blue button at top)

### Step 2: Fill in Basic Information
Fill in the following required fields:
- **Full Name** - User's complete name
- **Email Address** - Unique email for login
- **Password** - Minimum 8 characters
- **Confirm Password** - Repeat password for verification

### Step 3: Select Role
Choose the user's role from the dropdown:
- **Student** - Can submit clearance requests
- **Department Officer** - Approves clearance requests for a department
- **Registrar** - Manages academic records
- **Super Admin** - Full system access (usually not assigned during creation)

### Step 4: Fill Student Information (if role = Student)
If you selected "Student" role, additional fields appear:
- **Student ID** - Unique student identifier
- **Faculty** - Faculty name (e.g., "Faculty of Science")
- **Department** - Department name (e.g., "Computer Science")
- **Year** - Academic year (1-6)
- **Semester** - Current semester (1 or 2)
- **Gender** - Male, Female, or Other
- **Phone Number** - Contact number (optional)

### Step 5: Create User
Click **"Create User"** button to save the new user.

---

## Editing an Existing User

### Step 1: Navigate to Edit
1. Go to **Admin Dashboard** → **User Management**
2. Find the user in the list
3. Click the **Edit** button or user's name
4. Or use search to find the user quickly

### Step 2: Modify Information
You can update:
- Full name
- Email address
- Password (optional - leave blank to keep current)
- Role assignment
- Student information (if applicable)

### Step 3: Save Changes
Click **"Update User"** button to save changes.

---

## User Roles & Their Capabilities

| Role | Purpose | Permissions |
|------|---------|-------------|
| **Student** | Submit clearance requests | • Submit clearance requests<br>• Track request status<br>• View notifications |
| **Department Officer** | Approve clearance requests | • View pending clearances<br>• Approve/reject clearances<br>• Add remarks<br>• Receive notifications |
| **Registrar** | Academic records management | • Manage student records<br>• View clearance status<br>• Generate reports |
| **Super Admin** | Full system access | • Manage all users, roles, departments<br>• System configuration<br>• View all reports |

---

## Creating Different User Types

### Creating a Student User
```
1. Click "Add User"
2. Fill: Name, Email, Password
3. Select Role: "Student"
4. Fill Student Information:
   - Student ID: SAL/2024/001
   - Faculty: Faculty of Engineering
   - Department: Computer Science
   - Year: 2
   - Semester: 1
   - Gender: Male
5. Click "Create User"
```

### Creating a Department Officer
```
1. Click "Add User"
2. Fill: Name, Email, Password
3. Select Role: "Department Officer"
4. Click "Create User"
5. Assign to department separately (in Department Management)
```

### Creating a Registrar
```
1. Click "Add User"
2. Fill: Name, Email, Password
3. Select Role: "Registrar"
4. Click "Create User"
```

---

## Batch Operations

### Bulk Delete Users
1. Go to **User Management**
2. Select multiple users using checkboxes
3. Click **"Bulk Delete"** button
4. Confirm deletion in dialog

### Search & Filter Users
1. Use **Search Box** to find by:
   - Name
   - Email
   - Student ID
2. Use **Role Filter** to show specific role users:
   - Students
   - Officers
   - Registrars
   - Admins

### Export Users
Click **"Export"** button to download user list as CSV/Excel

---

## User Account Security

### Password Best Practices
- Minimum 8 characters required
- Mix uppercase and lowercase letters
- Include numbers and special symbols
- Avoid dictionary words
- Change password periodically

### Account Status
- **Active** - User can login and use system
- **Inactive** - User is disabled (set by deleting)

### User Impersonation (Admin Feature)
Admins can:
1. Login as another user temporarily for testing
2. Click **"Impersonate"** next to user
3. Browse as that user
4. Click **"Stop Impersonation"** to return

---

## User Management Statistics

The dashboard shows real-time statistics:
- **Total Users** - All users in system
- **Students** - Accounts with student role
- **Officers** - Department officers
- **Registrars** - Registrar staff
- **Admins** - Super admin accounts

---

## Common Tasks

### Task: Create 5 New Students
```
1. Go to User Management → Add User
2. For each student:
   - Name: [Student Name]
   - Email: [firstname.lastname@salale.edu]
   - Password: TempPassword123!
   - Role: Student
   - Fill student details
   - Click Create User
```

### Task: Create Department Staff
```
1. Go to User Management → Add User
2. Name: [Staff Name]
3. Email: [firstname.lastname@salale.edu]
4. Password: [Strong Password]
5. Role: Department Officer
6. Click Create User
7. Go to Department Management
8. Edit department (e.g., Book Store)
9. Assign officer to department
10. Set position and permissions
```

### Task: Change User Password
```
1. Go to User Management
2. Click Edit on user
3. Enter new password (optional field)
4. Confirm password
5. Click Update User
```

### Task: Deactivate User Account
```
1. Go to User Management
2. Select user checkbox
3. Click Bulk Delete (or use Edit → Delete)
4. Confirm deletion
```

---

## Troubleshooting

### Issue: Email Already Exists
- **Problem:** Error saying email is already in use
- **Solution:** Use a different email address or find existing user and edit them

### Issue: Password Too Short
- **Problem:** Password must be 8+ characters
- **Solution:** Use stronger password with mix of letters, numbers, symbols

### Issue: Cannot Assign Department Officer
- **Problem:** Department Officer role not showing in edit
- **Solution:** Create user first with officer role, then assign to department separately

### Issue: Student Fields Not Appearing
- **Problem:** Student information form hidden
- **Solution:** Make sure you select "Student" from Role dropdown to show fields

---

## API Reference (for Integration)

### Create User via API
```php
POST /api/users
{
    "name": "John Doe",
    "email": "john@salale.edu",
    "password": "Password123!",
    "role": "student",
    "student_id": "SAL/2024/001",
    "faculty": "Engineering",
    "department": "CS",
    "year": 2,
    "semester": 1
}
```

### Get All Users
```php
GET /api/users
GET /api/users?role=student
GET /api/users?search=john
```

### Update User
```php
PUT /api/users/{id}
{
    "name": "Jane Doe",
    "email": "jane@salale.edu"
}
```

### Delete User
```php
DELETE /api/users/{id}
```

---

## Security Notes

✓ Only Super Admins can create/manage users
✓ Passwords are encrypted and never shown
✓ All user actions are logged
✓ Users cannot delete themselves
✓ Email addresses are unique per user
✓ Student ID must be unique
✓ All forms have CSRF protection

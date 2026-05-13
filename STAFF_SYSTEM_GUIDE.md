## Multi-Staff Department System - Implementation Guide

### Problem Solved
Previously, each department could only have ONE officer. Now, departments can have MULTIPLE staff members, and the system identifies them by:
- **Department** they belong to
- **Position** (head, assistant, staff)
- **Can Approve** permission flag

---

### Database Structure

**New Table: `department_user` (Pivot Table)**
```
- department_id (FK)
- user_id (FK)
- position (string) - 'head', 'assistant', 'staff'
- can_approve (boolean) - True/False for approval permission
- created_at, updated_at
```

**Unique Constraint:** `(department_id, user_id)` - prevents duplicate staff assignments

---

### How Staff Are Identified

#### 1. By Department Slug
```php
$department = Department::where('slug', 'book-store')->first();
$staff = $department->staff; // Get all staff in Book Store
```

#### 2. By Department Name
```php
$department = Department::where('name', 'Book Store')->first();
$approvingStaff = $department->allStaff(); // Get staff who can approve
```

#### 3. By User's Departments
```php
$user = User::find(20);
$departments = $user->departments; // Get all departments this user belongs to
$position = $user->departments->find(1)->pivot->position; // Get position in dept 1
```

---

### Current Seeded Departments & Staff

| Department | Staff Count | Staff Members |
|------------|-------------|---------------|
| School / Department | 2 | Dr. John Smith (head), Mrs. Sarah Wilson (staff) |
| Book Store | 2 | Mr. Ahmed Hassan (head), Ms. Fatima Ali (staff) |
| Library | 3 | Mr. James Brown (head), Ms. Rose Johnson (staff), Mr. David Lee (staff) |
| Food Service | 2 | Mr. Ibrahim Yusuf (head), Ms. Amina Mohamed (staff) |
| Housing | 2 | Mr. Peter Okoro (head), Ms. Grace Okafor (staff) |
| Store Keeper | 1 | Mr. Kofi Mensah (head) |
| Campus Security | 3 | Mr. Samuel Osei (head), Mr. Marcus Kwesi (staff), Mr. Amos Amoah (staff) |
| Registrar Office | 2 | Mrs. Abigail Mensah (head), Mr. Benjamin Owusu (staff) |
| ICT Center | 2 | Dr. Kwame Asante (head), Mr. Robert Boateng (staff) |
| Finance Office | 2 | Mr. Stephen Agyeman (head), Ms. Elizabeth Darko (staff) |
| Clinic | 2 | Dr. Nana Agyeman (head), Ms. Comfort Adeyemi (staff) |

**Total: 24 staff members across 11 departments**

---

### How Notifications Work Now

When a student submits a clearance request:

1. **Event**: `ClearanceSubmitted` is dispatched
2. **Listener**: `NotifyDepartments` handles the event
3. **Process**:
   ```php
   $departments = Department::where('is_active', true)
       ->with('staff')
       ->get();
   
   foreach ($departments as $department) {
       // Get ALL staff in this department who can approve
       $staff = $department->allStaff();
       
       foreach ($staff as $person) {
           // Send notification to EACH staff member
           sendNotification($person, $clearance, $department);
       }
   }
   ```

**Result**: Every staff member in every department receives a notification, not just one officer!

---

### Code Examples

#### Add staff to a department
```php
$department = Department::find(1);
$user = User::find(20);

$department->staff()->attach($user->id, [
    'position' => 'head',
    'can_approve' => true,
]);
```

#### Get staff in specific department
```php
$department = Department::where('slug', 'book-store')->first();
$staff = $department->staff; // All staff
$approvingStaff = $department->allStaff(); // Only those who can approve
```

#### Check if user is in department
```php
$user = User::find(20);
$bookStore = Department::where('slug', 'book-store')->first();

if ($user->departments->contains($bookStore)) {
    echo "User works in Book Store";
}
```

#### Get user's position in a department
```php
$user = User::find(20);
$bookStore = Department::find(1);
$position = $user->departments()->find($bookStore)->pivot->position;
// Returns: 'head', 'assistant', or 'staff'
```

---

### Modified Files

1. **Migration**: `2026_05_13_create_department_user_table.php`
   - Creates the pivot table
   - Adds unique constraint

2. **Models**:
   - `User::departments()` - Many-to-many relationship
   - `Department::staff()` - Many-to-many relationship
   - `Department::allStaff()` - Get approvable staff

3. **Listeners**:
   - `NotifyDepartments` - Now sends to ALL staff per department

4. **Seeders**:
   - `DepartmentStaffSeeder` - Assigns 24 staff members
   - `DatabaseSeeder` - Added DepartmentStaffSeeder call

---

### Adding More Staff Manually

```php
$bookStore = Department::where('slug', 'book-store')->first();
$newStaff = User::factory()->create([
    'name' => 'New Employee',
    'email' => 'newemp@salale.edu',
]);

$bookStore->staff()->attach($newStaff->id, [
    'position' => 'staff',
    'can_approve' => true,
]);
```

---

### System Now Supports

✅ **Multiple staff per department** - Each department can have as many staff as needed
✅ **Position tracking** - Know who is the head vs regular staff
✅ **Approval permissions** - Control who can actually approve
✅ **Cross-department staff** - Same person can work in multiple departments
✅ **Automatic notifications** - All eligible staff get notified simultaneously
✅ **Easy querying** - Find staff by department or vice versa

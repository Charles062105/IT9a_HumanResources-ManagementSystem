# Comprehensive UI/UX Review: Employee Module

**Review Date**: May 13, 2026  
**Scope**: `resources/views/employees/{index, show, create, edit}.blade.php`  
**Severity Ratings**: 🔴 Critical | 🟠 High | 🟡 Medium | 🟢 Low

---

## Executive Summary

The employee module has **21 inline styles**, **inline event handlers** using deprecated patterns, **missing datalist autocompletes**, and **partial mobile responsiveness**. The module functions but has UX friction points and accessibility concerns.

---

## 1. employee/index.blade.php

### ✅ What Works Well
- Filter bar uses semantic HTML with proper form controls
- Status badges with color-coding system
- Role badges properly implemented
- Pagination layout included

### 🔴 CRITICAL Issues

#### 1.1 Inline Event Handlers - Lines 104-106 & 108-110
```blade
<form method="POST" action="{{ route('employees.deactivate', $emp) }}" 
      onsubmit="return confirm('Deactivate this employee?')" 
      class="action-form">
```
**Problems:**
- Uses deprecated inline `onsubmit="return confirm()"` pattern
- Not accessible to screen readers
- No ARIA attributes
- Confirms happen in browser alert (poor UX)

**Recommendation:** Extract to JavaScript module or use Alpine/Livewire modals
```blade
<form method="POST" action="{{ route('employees.deactivate', $emp) }}"
      class="action-form employee-action"
      data-action="deactivate"
      data-employee="{{ $emp->id }}">
```

#### 1.2 Missing Features - Lines 1-138
**Issues:**
- ❌ No column sorting (Name, Department, Date Hired all static)
- ❌ No bulk actions (select multiple employees for batch deactivation)
- ❌ No export functionality (CSV/Excel)
- ❌ No "View Attendance", "View Violations", "View Performance" quick-links

---

### 🟠 HIGH Priority Issues

#### 1.3 Filter Bar Layout on Mobile - Line 13
CSS: `filter-bar` with `flex-wrap: wrap` (responsive ✓)

**Found in components.css @media (max-width: 640px):**
```css
.filter-bar > * {
  flex: 1 1 calc(50% - 4px);  /* Two columns */
  min-width: 100px;
}
```

**Issue:** Filter controls stack to 50% width on mobile
- Search input wraps awkwardly
- Department/Position selects become cramped
- Results counter (`f-results`) moves to new line

**Better approach:** Stack all to 100% width on mobile, show as vertically expanding accordion

#### 1.4 Table Overflow on Mobile - Line 13 (table-wrap)
**CSS Rule (components.css @media 640px):**
```css
th:nth-child(n+5), td:nth-child(n+5) { display: none; }
```
**Current columns on mobile:** Employee ID, Name, Status (keep these)  
**Missing:** No visual indication that columns are hidden

**Improvement:** Add a "more" indicator or expand button per row

#### 1.5 Long Name/Department Overflow - Line 68-71
```blade
<span class="td-bold">{{ $emp->full_name }}</span>
<td class="td-muted">{{ $emp->department }}</td>
```
**No CSS truncation applied.**  
**Fix:** Add Tailwind utilities or CSS class
```blade
<span class="td-bold truncate">{{ $emp->full_name }}</span>
<td class="td-muted truncate">{{ $emp->department }}</td>
```

#### 1.6 Action Group Alignment - Line 97
```blade
<div class="action-group">
  <a href="{{ route('employees.show', $emp) }}" class="action-link">View</a>
  <a href="{{ route('employees.edit', $emp) }}" class="action-link">Edit</a>
  <form ...>...</form>
</div>
```
**Issue:** Mixing `<a>`, inline `<form>` causes misalignment and layout shifts  
**On mobile:** Action buttons likely overflow

---

### 🟡 MEDIUM Priority Issues

#### 1.7 Pagination Layout - Line 135
```blade
@if($employees->hasPages())
  <div class="pagination-wrap">{{ $employees->links() }}</div>
@endif
```
**Issues:**
- No "Showing X of Y" metadata
- No "rows per page" selector
- Pagination not tested on mobile (<640px)

#### 1.8 Missing Status Meaning - Line 85
Four statuses defined but no help text:
- `active` ✓
- `probationary` - When used? Duration?
- `contractual` - Tied to contract_expiry?
- `inactive` - Permanent or reversible?

---

### 🟢 LOW Priority Observations

#### 1.9 Employee Avatar Fallback
Avatar uses initials (✓ Good), but no color consistency - uses same hardcoded color for all

#### 1.10 Date Format Consistency
Using `M j, Y` format (e.g., "May 13, 2026") - consistent ✓

---

## 2. employee/show.blade.php

### ✅ What Works Well
- Clean profile card layout
- Government ID section properly labeled
- Status display with color badges
- Attendance/Violations tables included

### 🔴 CRITICAL Issues

#### 2.1 Excessive Inline Styles - COUNT: 21 Direct Inline Styles
**Lines with inline styles:**

1. **Line 7** - Page header actions container
   ```blade
   <div style="display:flex;gap:8px">
   ```

2. **Line 8** - Edit button (7 CSS properties)
   ```blade
   style="text-decoration:none;display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:7px;font-size:12px"
   ```
   ⚠️ This is a `.btn-primary` - should use button class instead

3. **Line 24** - Profile card body centering
   ```blade
   style="text-align:center;padding:24px"
   ```

4. **Line 26** - Avatar circle (6 properties)
   ```blade
   style="width:64px;height:64px;background:#1A4D8F;color:#fff;font-size:22px;font-weight:700;margin:0 auto 12px;border-radius:50%"
   ```
   ⚠️ Hardcoded color `#1A4D8F` - not using CSS variable

5. **Line 29** - Full name text
   ```blade
   style="font-size:15px;font-weight:600;color:var(--text)"
   ```

6. **Line 30** - Position text
   ```blade
   style="font-size:12px;color:var(--text3);margin-top:3px"
   ```

7. **Line 31** - Department text
   ```blade
   style="font-size:12px;color:var(--text3)"
   ```

8. **Line 32** - Status badge margin
   ```blade
   style="margin:14px 0"
   ```

9. **Line 34** - Years of service text
   ```blade
   style="font-size:11px;color:var(--text3)"
   ```

10. **Line 37** - Details section border
    ```blade
    style="border-top:1px solid var(--border);padding:14px 18px"
    ```

11. **Line 38** - Field row (flex, spacing)
    ```blade
    style="display:flex;justify-content:space-between;padding:5px 0;border-bottom:1px solid var(--border);font-size:11px"
    ```
    ⚠️ Repeated multiple times (Lines 52-54, etc.)

12. **Line 40** - Label text
    ```blade
    style="color:var(--text3)"
    ```

13. **Line 41** - Value text
    ```blade
    style="font-weight:500;color:var(--text)"
    ```

14. **Line 49** - Government IDs section
    ```blade
    style="border-top:1px solid var(--border);padding:14px 18px"
    ```
    ⚠️ Duplicates line 37

15. **Line 50** - Government IDs title
    ```blade
    style="font-size:10px;font-weight:600;color:var(--text3);text-transform:uppercase;letter-spacing:0.5px;margin-bottom:8px"
    ```

16. **Line 52** - Government ID row (duplicates line 38)
    ```blade
    style="display:flex;justify-content:space-between;padding:4px 0;font-size:11px"
    ```

17. **Line 53** - Gov ID label
    ```blade
    style="color:var(--text3)"
    ```

18. **Line 54** - Gov ID value
    ```blade
    style="font-weight:500;font-family:monospace;font-size:10px;color:var(--text)"
    ```

19. **Line 60** - Stats container
    ```blade
    style="display:flex;flex-direction:column;gap:14px"
    ```

20. **Line 67** - Violations KPI number
    ```blade
    style="{{ $employee->violations->where('status','open')->count() > 0 ? 'color:var(--danger)' : '' }}"
    ```
    ⚠️ Inline conditional styling

21. **Line 157** - Empty state in table
    ```blade
    style="padding:16px"
    ```

**Total: 21+ inline styles (some are repeated patterns)**

**Impact:**
- ❌ Hard to maintain globally
- ❌ Dark mode/theme changes require file edits
- ❌ No reusable component styling
- ❌ Performance: inline styles can't be cached

**Recommendation:** Create reusable Blade components:
```blade
<!-- resources/views/components/profile-card.blade.php -->
<div class="profile-card">
  <div class="profile-card__body">
    <div class="avatar avatar--lg">{{ $employee->initials }}</div>
    <h1 class="profile-card__title">{{ $employee->full_name }}</h1>
  </div>
</div>
```

#### 2.2 Profile Grid Not Responsive - Line 20
```blade
<div class="profile-grid">
  <div class="card">...</div>
  <div style="display:flex;flex-direction:column;gap:14px">...</div>
</div>
```

**Issue:** 
- CSS class `profile-grid` is not defined in `components.css`
- Likely using default grid behavior
- ⚠️ **Grid layout untested on tablet (768px) - no @media rule covers profile-grid**
- On mobile, right column will stack but no optimization

**Missing CSS:**
```css
.profile-grid {
  display: grid;
  grid-template-columns: 1fr 2fr;  /* Profile card + stats/tables */
  gap: 24px;
}

@media (max-width: 768px) {
  .profile-grid {
    grid-template-columns: 1fr;  /* Stack on tablet */
  }
}
```

#### 2.3 Stats Grid (KPI) Mobile Layout - Line 62
```blade
<div class="stats-grid">
  <div class="kpi">...</div>
  <div class="kpi">...</div>
  <div class="kpi">...</div>
</div>
```

**CSS Found:** `@media (max-width: 640px) { .kpi { padding: 12px; font-size: 12px; } }`

**Issues:**
- `stats-grid` class styling not defined in components.css
- KPI styling exists but grid definition missing
- Numbers might be too small on mobile: `font-size: 12px` for `.kpi-num` (should be 18px minimum)
- Gap between KPIs not defined

**Missing CSS:**
```css
.stats-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 16px;
  margin: 16px 0;
}

@media (max-width: 768px) {
  .stats-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 640px) {
  .stats-grid {
    grid-template-columns: 1fr;
  }
}
```

#### 2.4 Table Layouts on Mobile - Lines 117, 130
Recent Attendance table and Violations table have many columns

**Columns on Attendance table:**
- Date | Time In | Time Out | Hours | Status

**On mobile (640px):** Table will scroll horizontally - not ideal for 5 columns

**Columns on Violations table:**
- Level | Offense | # | Status | Date

**Better approach:** Use a "card" layout on mobile instead of table

---

### 🟠 HIGH Priority Issues

#### 2.5 Edit Button Accessibility - Line 8
```blade
<a href="{{ route('employees.edit', $employee) }}" class="btn-primary" 
   style="text-decoration:none;display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:7px;font-size:12px">
   Edit
</a>
```

**Issues:**
- ✓ Uses semantic link (good)
- ❌ Inline styles override `.btn-primary` (inconsistent)
- ❌ No icon included (says "Edit" but header suggests there should be one)
- ❌ Font size 12px too small (should be 14px like other buttons)

**Fix:**
```blade
<a href="{{ route('employees.edit', $employee) }}" class="btn-primary">
  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
    <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/>
  </svg>
  Edit
</a>
```

#### 2.6 Government IDs Monospace Font - Line 54
```blade
style="font-weight:500;font-family:monospace;font-size:10px;color:var(--text)"
```

**Issues:**
- ❌ Font size 10px too small for IDs (should be 12px or inherit)
- ❌ Monospace only helps with fixed-width numbers but IDs have varying formats:
  - SSS: `00-0000000-0` (11 chars)
  - Pag-IBIG: `0000-0000-0000` (14 chars)
  - PhilHealth: `00-000000000-0` (14 chars)

**Better:**
```blade
style="font-weight:500;font-family:monospace;font-size:12px;color:var(--text);word-break:break-word"
```

#### 2.7 Color Consistency Issues
**Line 26:** Avatar uses hardcoded `#1A4D8F`
- Should use CSS variable or computed from initials
- Other avatars likely have different hardcoded colors

**Line 67:** Violations count conditional styling
```blade
style="{{ $employee->violations->where('status','open')->count() > 0 ? 'color:var(--danger)' : '' }}"
```
- Only changes color if violations > 0
- Should always show number, use badge styling instead

---

### 🟡 MEDIUM Priority Issues

#### 2.8 Section Headers Missing - Lines 37-54
Profile fields and Government IDs lack visual section breaks

**Better structure:**
```blade
<div class="profile-section">
  <h3 class="profile-section__title">Contact Information</h3>
  <div class="profile-field">...</div>
</div>
```

#### 2.9 Missing Features
- ❌ No "Print Profile" button
- ❌ No "Download Profile as PDF" option
- ❌ No "View Timeline" or activity history
- ❌ No "Quick Actions" menu (message, send notification, etc.)
- ❌ No link to employee's timesheets/leaves from profile

#### 2.10 Table Action Columns
Recent Attendance and Violations tables show data but no actions:
- No "view details" links
- No "generate report" buttons

---

### 🟢 LOW Priority Observations

#### 2.11 Empty State Styling - Line 157
```blade
<td colspan="5"><div class="empty-state" style="padding:16px">No attendance records</div></td>
```
- Only 16px padding on empty state (inconsistent with other empty states)
- Should use default empty-state styling

#### 2.12 Tab Consistency
Left card shows personal details, right side shows stats/history
- No visual grouping (should use card sections with headers)
- Scrollable content on mobile (right column is tall)

---

## 3. employee/create.blade.php

### ✅ What Works Well
- Clear form section titles with dividers
- Help text provided for complex fields
- Status select for employment type
- Government ID format hints via `title` and `placeholder`

### 🔴 CRITICAL Issues

#### 3.1 MISSING Datalist for Department & Position - Lines 89-97
**Current code:**
```blade
<label for="department">Department <span class="required">*</span></label>
<input type="text" id="department" name="department" 
       value="{{ old('department') }}" 
       placeholder="e.g. Engineering" required 
       class="{{ $errors->has('department') ? 'input-error' : '' }}">

<label for="position">Position <span class="required">*</span></label>
<input type="text" id="position" name="position" 
       value="{{ old('position') }}" 
       placeholder="e.g. Software Engineer" required 
       class="{{ $errors->has('position') ? 'input-error' : '' }}">
```

**Problems:**
- ❌ Free text input = inconsistent data (typos, variations)
- ❌ No autocomplete suggestions
- ❌ Users must remember exact department/position names
- ❌ Index page shows these values (hard to be consistent)

**Compare with edit.blade.php (Lines 138-150)** - Has datalists! ✓

**Code in edit.blade.php:**
```blade
<input type="text" id="department" name="department" 
       value="{{ old('department', $employee->department) }}" 
       list="departments" required>
<datalist id="departments">
  @foreach($departments as $dept)
    <option value="{{ $dept }}">
  @endforeach
</datalist>
```

**FIX:** Update create.blade.php to include the same datalist:
```blade
<label for="department">Department <span class="required">*</span></label>
<input type="text" id="department" name="department" 
       value="{{ old('department') }}" 
       list="departments" required 
       class="{{ $errors->has('department') ? 'input-error' : '' }}">
<datalist id="departments">
  @foreach($departments as $dept)
    <option value="{{ $dept }}">
  @endforeach
</datalist>
```

**Same for position (add list="positions")**

---

### 🟠 HIGH Priority Issues

#### 3.2 Government ID Format Hints Unclear - Lines 175-183
```blade
<label for="sss_number">SSS Number</label>
<input type="text" id="sss_number" name="sss_number" 
       value="{{ old('sss_number') }}" 
       placeholder="00-0000000-0" 
       title="Format: 00-0000000-0">
```

**Issues:**
- ❌ `title` attribute only shows on hover (not discoverable)
- ❌ Format shown in placeholder but truncated on small screens
- ❌ No visible format validation or input masking
- ❌ No error message if format is wrong

**Better approach:**
```blade
<label for="sss_number">
  SSS Number
  <span class="form-hint">Format: XX-XXXXXXX-X</span>
</label>
<input type="text" id="sss_number" name="sss_number" 
       value="{{ old('sss_number') }}" 
       placeholder="00-0000000-0" 
       pattern="^\d{2}-\d{7}-\d{1}$"
       title="Format: 00-0000000-0 (digits and hyphens only)"
       class="{{ $errors->has('sss_number') ? 'input-error' : '' }}">
@error('sss_number')<div class="error-msg">{{ $message }}</div>@enderror
```

#### 3.3 Form Mobile Responsiveness - Lines 1-191
**Uses `.form-row` class** which is responsive:
```css
@media (max-width: 640px) {
  .form-row {
    grid-template-columns: 1fr !important;
  }
}
```
✓ Stacks to single column on mobile

**However:**
- No overflow testing done on textareas (address field)
- Input fields might exceed viewport width without `max-width: 100%`
- Submit buttons might not have sufficient width on mobile

#### 3.4 Form Layout Consistency - Lines 40-57
```blade
<div class="form-row">
  <div class="form-group">
    <label for="first_name">First Name <span class="required">*</span></label>
    ...
  </div>
  <div class="form-group">
    <label for="last_name">Last Name <span class="required">*</span></label>
    ...
  </div>
</div>

<div class="form-row">
  <div class="form-group">
    <label for="email">Email Address <span class="required">*</span></label>
    ...
  </div>
  ...
</div>
```

**Observation:** Inconsistent field pairing
- First/Last Name: 2 columns ✓
- Email/Phone: 2 columns ✓
- Address: Full width ✓
- Department/Position: 2 columns ✓
- Date Hired/Status: 2 columns ✓
- Shift/Contract Expiry: 2 columns ✓
- SSS/Pag-IBIG: 2 columns ✓
- PhilHealth: Single (looks odd)

**Better:** PhilHealth should span full width or be grouped with another field

---

### 🟡 MEDIUM Priority Issues

#### 3.5 Help Text Clarity - Lines 108, 115, 128
```blade
<div class="form-help">Must be today or earlier</div>
<div class="form-help">Assign employee to a work shift</div>
<div class="form-help">Required for contractual employees</div>
```

**Issues:**
- Help text is small and gray - easily missed
- "Must be today or earlier" - is past date OK?
- "Required for contractual employees" - should be dynamic (only show if status=contractual)

**Better:**
```blade
<input type="date" id="date_hired" name="date_hired" 
       value="{{ old('date_hired') }}" required>
<div class="form-help">
  Must not be in the future
  <svg class="icon-sm" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5zm-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5 7.67 11 8.5 11zm3.5 6.5c2.33 0 4.31-1.46 5.11-3.5H6.89c.8 2.04 2.78 3.5 5.11 3.5z"/></svg>
</div>
```

#### 3.6 Date Picker Consistency
Three date fields: `date_of_birth`, `date_hired`, `contract_expiry`
- No consistent validation across browsers (mobile date picker vs. desktop)
- No min/max constraints on HTML level

**Add constraints:**
```blade
<input type="date" id="date_hired" name="date_hired" 
       max="{{ now()->format('Y-m-d') }}"
       value="{{ old('date_hired') }}" required>

<input type="date" id="contract_expiry" name="contract_expiry" 
       min="{{ now()->format('Y-m-d') }}"
       value="{{ old('contract_expiry') }}">
```

#### 3.7 Shift Assignment Unclear
```blade
<label for="shift_id">Shift Assignment</label>
<select id="shift_id" name="shift_id" class="{{ $errors->has('shift_id') ? 'input-error' : '' }}">
  <option value="">-- No shift assigned --</option>
  @foreach($shifts ?? [] as $shift)
    <option value="{{ $shift->id }}">
      {{ $shift->name }} ({{ $shift->start_time }} - {{ $shift->end_time }})
    </option>
  @endforeach
</select>
```

**Issues:**
- Optional field but might be required by business logic
- Time format not specified (24h vs. 12h)
- Help text says "assign" but it's optional

---

### 🟢 LOW Priority Observations

#### 3.8 Required Field Indicator
Uses `<span class="required">*</span>` - consistent ✓

#### 3.9 Button Consistency
```blade
<button type="submit" class="btn-primary">Save Employee</button>
<a href="{{ route('employees.index') }}" class="btn-secondary">Cancel</a>
```
✓ Good button styling

#### 3.10 Section Dividers
Uses `<div class="divider"></div>` - consistent ✓

---

## 4. employee/edit.blade.php

### ✅ What Works Well
- Datalists for department/position ✓
- Form help text present
- Role management section included
- Revoke admin warning shown

### 🔴 CRITICAL Issues

#### 4.1 Inline JavaScript - 3 Event Handlers
**Lines 215, 235, 253 have inline handler attributes:**

1. **Line 215 - toggleContractExpiry:**
   ```blade
   <select id="empStatus" name="status" required aria-required="true" 
           onchange="toggleContractExpiry(this.value)">
   ```
   - ❌ Inline handler
   - ✓ Function defined at bottom (Lines 295-308)

2. **Line 235 - updateRole:**
   ```blade
   <select id="role" name="role" required 
           class="{{ $errors->has('role') ? 'input-error' : '' }}" 
           onchange="updateRole(this.value, {{ $employee->id }})">
   ```
   - ❌ Inline handler with inline data
   - ❌ Makes AJAX call on change (NO CONFIRMATION!)
   - ❌ Role updates immediately without user confirmation

3. **Line 253 - setLoading:**
   ```blade
   <button type="submit" class="btn-primary" id="submitBtn" 
           onclick="setLoading(event)">
   ```
   - ❌ Inline handler
   - Possible race condition: if user double-clicks, second submit might go through

#### 4.2 Script Block (Lines 288-351) - Mixed Concerns
```javascript
function setLoading(event) { ... }
function toggleContractExpiry(val) { ... }
function updateRole(value, employeeId) { ... }
// Plus form dirty state tracking
```

**Issues:**
- ❌ All JavaScript inline in view file (not modular)
- ❌ Functions globally scoped (conflicts with other pages)
- ❌ No error boundaries
- ❌ updateRole() has fetch but no loading state
- ❌ Form dirty checking can interfere with the updateRole() fetch

**Better architecture:**
```
resources/
  js/
    pages/
      employee-edit.js    <- Import this in view
```

#### 4.3 Role Update UX - Line 235
```blade
<select id="role" name="role" required class="..." onchange="updateRole(this.value, {{ $employee->id }})">
  @foreach($roles as $r)
    <option value="{{ $r }}" {{ ($employee->user?->role ?? 'employee') == $r ? 'selected' : '' }}>
      {{ ucfirst(str_replace('_', ' ', $r)) }}
    </option>
  @endforeach
</select>
```

**CRITICAL UX ISSUE:**
- User changes role → Immediate AJAX call (no confirmation)
- If user clicks "super_admin" by accident, role is changed instantly
- No undo capability

**JavaScript (Lines 308-330):**
```javascript
function updateRole(value, employeeId) {
  if (!value) return;
  fetch(`/employees/${employeeId}/role`, {
    method: 'PATCH',
    ...
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      const help = document.querySelector('.form-group:has(#role) .form-help');
      help.textContent = value === 'employee' ? 'Regular employee account' : 'Admin access - can manage system';
    }
  })
  .catch(() => {
    alert('Failed to update role. Please try again.');
    location.reload();
  });
}
```

**Problems:**
- ❌ No confirmation modal
- ❌ No loading indicator on the select
- ❌ Error recovery is page reload (loses all form changes)
- ❌ Help text updates immediately (feels incomplete)

**Recommendation:** Remove `onchange` handler, make role update part of form submission or use a dedicated modal

#### 4.4 Revoke Admin Section Styling - Lines 248-256
```blade
<div class="form-row full">
  <div class="revoke-admin-section">
    <div class="revoke-admin-warning">
      <svg>...</svg>
      <div>
        <div class="revoke-admin-title">Revoke Admin Access</div>
        <div class="revoke-admin-text">{{ $employee->full_name }} currently has {{ ucfirst(...) }} role</div>
      </div>
    </div>
    <a href="{{ route('users.revoke-admin-form', $employee->user) }}" class="btn-danger">Revoke Admin Role</a>
  </div>
</div>
```

**Issues:**
- ❌ `.revoke-admin-section` class styling not found in CSS (undefined!)
- ❌ `.revoke-admin-warning` class styling not found (likely inline?)
- ❌ `.revoke-admin-title` class styling not found
- ❌ `.revoke-admin-text` class styling not found
- ⚠️ Warning icon has no label (accessibility issue)
- Only shows if `$employee->user?->isAdmin()` (line 247) - good conditional

**Missing CSS likely needed:**
```css
.revoke-admin-section {
  padding: 14px;
  background: #fef3c7;  /* Warn color background */
  border: 1px solid #fcd34d;
  border-radius: 6px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 12px;
}

.revoke-admin-warning {
  display: flex;
  gap: 8px;
  align-items: flex-start;
  flex: 1;
}

.revoke-admin-title {
  font-weight: 600;
  font-size: 14px;
  color: #78350f;
}

.revoke-admin-text {
  font-size: 12px;
  color: #92400e;
  margin-top: 2px;
}
```

---

### 🟠 HIGH Priority Issues

#### 4.5 Government ID Format Display - Lines 170-183
Same issue as create.blade.php - format hints in title/placeholder only

**Current:**
```blade
<label for="sss_number">SSS number</label>
<input type="text" id="sss_number" name="sss_number" 
       value="{{ old('sss_number', $employee->sss_number) }}" 
       placeholder="00-0000000-0" 
       title="Format: 00-0000000-0" 
       class="{{ $errors->has('sss_number') ? 'input-error' : '' }}">
```

**Issues:**
- ❌ No validation error if format is wrong (on backend)
- ❌ No client-side input masking
- ❌ Placeholder doesn't show format clearly

**Better:**
```blade
<label for="sss_number">
  SSS number
  <span class="form-hint" title="Social Security System">XX-XXXXXXX-X</span>
</label>
<input type="text" id="sss_number" name="sss_number" 
       value="{{ old('sss_number', $employee->sss_number) }}" 
       placeholder="00-0000000-0"
       pattern="^\d{2}-\d{7}-\d{1}$"
       title="Format: 00-0000000-0"
       class="{{ $errors->has('sss_number') ? 'input-error' : '' }}">
@error('sss_number')<div class="error-msg">{{ $message }}</div>@enderror
```

#### 4.6 Government ID Format Validation Missing
No validation backend shown - assumes controller has validation

**Check:** Should have FormRequest validation like:
```php
// app/Http/Requests/UpdateEmployeeRequest.php
'sss_number' => 'nullable|regex:/^\d{2}-\d{7}-\d{1}$/',
'pagibig_number' => 'nullable|regex:/^\d{4}-\d{4}-\d{4}/',
'philhealth_number' => 'nullable|regex:/^\d{2}-\d{9}-\d{1}/',
```

#### 4.7 Contract Expiry Toggle - Line 215
```blade
<select id="empStatus" name="status" required aria-required="true" 
        onchange="toggleContractExpiry(this.value)">
```

**Function (Lines 295-308):**
```javascript
function toggleContractExpiry(val) {
  const input = document.getElementById('contractExpiryInput');
  const label = document.getElementById('contractExpiryLabel');
  const help = document.getElementById('contractExpiryHelp');

  if (val === 'contractual') {
    input.required = true;
    label.innerHTML = 'Contract expiry date <span class="required">*</span>';
    help.innerHTML = 'Required for contractual employees — alerts will trigger 30 days before.';
  } else {
    input.required = false;
    input.value = '';
    label.textContent = 'Contract expiry date';
    help.textContent = 'Required for contractual employees';
  }
}
```

**Issues:**
- ✓ Function works correctly
- ❌ Uses `.innerHTML` (security risk - could be XSS if label is user-input)
- ❌ Mixes `.innerHTML` and `.textContent` (inconsistent)
- ⚠️ Clears contract_expiry value when status changes away from "contractual" - might lose data!
- ❌ No DOMContentLoaded check before function definition (works here due to script position)

**Better:**
```javascript
function toggleContractExpiry(val) {
  const input = document.getElementById('contractExpiryInput');
  const label = document.getElementById('contractExpiryLabel');
  const help = document.getElementById('contractExpiryHelp');

  if (val === 'contractual') {
    input.required = true;
    input.classList.add('required');
    label.classList.add('label--required');
    help.textContent = 'Required for contractual employees — alerts will trigger 30 days before.';
  } else {
    input.required = false;
    input.classList.remove('required');
    label.classList.remove('label--required');
    help.textContent = 'Required for contractual employees';
    // DO NOT clear input.value - preserve user data
  }
}
```

#### 4.8 Form Dirty State Tracking - Lines 334-351
```javascript
let formDirty = false;

document.addEventListener('DOMContentLoaded', function() {
  // ...
  form.addEventListener('input', function() {
    formDirty = true;
  });

  form.addEventListener('change', function(e) {
    if (e.target.id !== 'role') formDirty = true;  // Exclude role changes
  });

  form.addEventListener('submit', function() {
    formDirty = false;
  });

  window.addEventListener('beforeunload', function(e) {
    if (formDirty) {
      e.preventDefault();
      e.returnValue = '';
      return '';
    }
  });
});
```

**Issues:**
- ✓ Prevents accidental navigation (good)
- ⚠️ Excludes role changes because `updateRole()` makes AJAX call
- ❌ If role update fails, formDirty stays true but user is confused
- ❌ Multiple forms on page would all set/clear the same global flag
- ⚠️ `beforeunload` can be annoying if user has legitimate reasons to leave

---

### 🟡 MEDIUM Priority Issues

#### 4.9 Form Mobile Responsiveness - General
Uses `.form-row` responsive class (same as create) ✓

**But:** No specific testing for:
- Edit button width on mobile (near back button)
- Revoke admin section layout on mobile
- Government ID inputs on narrow screens

#### 4.10 Page Header Actions - Line 9
```blade
<div class="page-actions">
  <a href="{{ route('employees.show', $employee) }}" class="btn-secondary">View Profile</a>
  <a href="{{ route('employees.index') }}" class="btn-secondary">← Back</a>
</div>
```

**Issues:**
- 2 buttons might overflow on mobile
- No flex-wrap or responsive handling

#### 4.11 Role Help Text Conditional - Lines 237-238
```blade
<div class="form-help">{{ ($employee->user?->role ?? 'employee') == 'employee' ? 'Regular employee account' : 'Admin access - can manage system' }}</div>
```

**Issues:**
- Help text hardcoded in Blade (repeats logic from JS)
- If role is updated via JS, help text is updated but not persistent (page reload shows server value)
- Ternary is complex, hard to read

---

### 🟢 LOW Priority Observations

#### 4.12 Aria-Required Attributes
Lines 25, 32, 44, 217 use `aria-required="true"` ✓ Good accessibility

#### 4.13 Missing CSRF Protection
All forms have `@csrf` ✓

#### 4.14 Old Value Preservation
Uses `old()` helper for form validation errors ✓

---

## Summary Table: Critical Findings

| File | Issue | Severity | Line(s) |
|------|-------|----------|---------|
| **index.blade.php** | Inline confirm() dialogs | 🔴 | 104-110 |
| **index.blade.php** | No column sorting | 🔴 | 45-58 |
| **index.blade.php** | No bulk actions | 🔴 | All |
| **show.blade.php** | 21 inline styles | 🔴 | 7, 8, 24, 26, 29-34, 37-54, 60, 67 |
| **show.blade.php** | Profile grid not responsive | 🔴 | 20 |
| **show.blade.php** | Stats grid missing CSS | 🔴 | 62 |
| **create.blade.php** | MISSING datalist for dept/position | 🔴 | 89-97 |
| **create.blade.php** | ID format hints in title only | 🟠 | 175-183 |
| **edit.blade.php** | 3 inline event handlers | 🔴 | 215, 235, 253 |
| **edit.blade.php** | Role update UX (no confirmation) | 🔴 | 235, 308-330 |
| **edit.blade.php** | Revoke admin styling undefined | 🔴 | 248-256 |
| **edit.blade.php** | Contract expiry clears value | 🟠 | 303 |
| **edit.blade.php** | .innerHTML XSS risk | 🟠 | 301 |

---

## Recommended Priority Fixes

### Phase 1: Critical (Blocking Issues)
1. ✅ Add datalist to create.blade.php (department/position)
2. ✅ Remove inline event handlers from edit.blade.php → extract to module
3. ✅ Add confirmation modal for role updates (edit.blade.php)
4. ✅ Extract 21 inline styles from show.blade.php → CSS classes
5. ✅ Add missing profile-grid and stats-grid CSS
6. ✅ Fix .revoke-admin-* CSS (currently undefined)

### Phase 2: High Priority (UX Friction)
7. ✅ Replace confirm() dialogs with modals (index.blade.php)
8. ✅ Add column sorting to employee table (index.blade.php)
9. ✅ Add bulk actions and export (index.blade.php)
10. ✅ Improve ID format validation with input masking
11. ✅ Move edit button to use `.btn-primary` class (not inline styles)
12. ✅ Fix contract_expiry clear on status change

### Phase 3: Medium Priority (Polish)
13. ✅ Add profile grid responsive CSS
14. ✅ Responsive table layouts (convert to card view on mobile)
15. ✅ Add missing quick-link buttons on profile
16. ✅ Improve help text visibility/clarity
17. ✅ Test pagination on mobile

---

## CSS Classes Needing Definition

```css
/* Missing from components.css */
.profile-grid { }
.profile-card { }
.profile-card__body { }
.profile-card__title { }
.profile-section { }
.profile-section__title { }
.profile-field { }
.stats-grid { }
.kpi-grid { }

.revoke-admin-section { }
.revoke-admin-warning { }
.revoke-admin-title { }
.revoke-admin-text { }

.form-hint { }
.label--required { }

/* Responsive fixes needed */
@media (max-width: 768px) {
  .profile-grid { grid-template-columns: 1fr; }
  .stats-grid { grid-template-columns: repeat(2, 1fr); }
  .page-actions { flex-wrap: wrap; }
}

@media (max-width: 640px) {
  .stats-grid { grid-template-columns: 1fr; }
  .page-actions > * { width: 100%; }
  table { /* card-view mode for small tables */ }
}
```

---

## Blade Components to Create

```
resources/views/components/
  ├── profile-card.blade.php
  ├── profile-section.blade.php
  ├── stats-grid.blade.php
  ├── kpi-card.blade.php
  ├── revoke-admin-warning.blade.php
  └── government-id-input.blade.php
```


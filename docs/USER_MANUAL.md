# PGECC User Manual

**System:** PGECC  
**Audience:** PGECC administrators and authorized data-entry users  
**Last reviewed:** September 2026

## 1. Start here: administrator setup and access

PGECC access begins with a super administrator or authorized user manager. The administrator signs in, creates the user account, and assigns only the permissions required for that person’s work.

### Create and authorize a user

1. Sign in using an administrator account.
2. Open **User Management**.
3. Enter the new user’s name, email address, and password.
4. Assign the allowed permissions:
   - **Upload files** (`upload_file`) for global and category-specific spreadsheet uploads;
   - **Manage member classes** (`manage_member_classes`) for configuring class limits;
   - **Manage users** (`manage_users`) for creating users and changing access.
5. Select **Create admin**.
6. Tell the new user to sign in and change the temporary password.

Assign the least access necessary. A user manager should not grant **Manage users** unless the person is authorized to administer other accounts.

## 2. Purpose

PGECC is an internal administration system for reviewing employee/member balances and maintaining uploaded records for Carenderia, Loans, Consumer Balances, Grocery, and Payments.

The system supports:

- searching for an employee/member by PMC ID or name;
- reviewing balance limits and usage by member class;
- importing spreadsheet data;
- tracking uploaded files and downloading source files;
- managing administrator access and member-class limits.

## 3. Access and sign-in

1. Open the PGECC address supplied by your system administrator.
2. Select **Admin login** if the sign-in window is not already open.
3. Enter your email address and password.
4. Select **Remember me** only on a trusted device, then select **Sign in**.

The application requires authentication for the dashboard and administrative pages. If the credentials are rejected, verify the email address and contact a system administrator if the problem continues.

### Change your password

1. Open the account menu at the bottom of the left sidebar.
2. Select **Change password**.
3. Enter your current password.
4. Enter a new password and confirm it. The new password must contain at least 8 characters.
5. Select **Update password**.

Select **Log out** when finished, especially on a shared computer.

## 4. Navigation and permissions

The left sidebar contains the pages available to the signed-in user. Access is controlled by permissions.

| Permission | Allows |
|---|---|
| `upload_file` | Upload the dashboard employee-balance file and upload data from module pages |
| `manage_member_classes` | Create, edit, and delete member classes and their limits |
| `manage_users` | Create administrators, review users, and update administrator permissions |

The **super admin** role has all permissions. A regular administrator sees only the tools assigned to that account.

The currently visible sidebar pages are **Dashboard**, **Carenderia**, **Loans**, **Consumer Balances**, and—when permitted—**User Management** and **Member Classes**. Grocery and Payments pages are implemented in the application but are currently hidden from the sidebar; an administrator may need to enable their navigation before normal users can reach them.

## 5. Dashboard and global data upload

The dashboard is the main place to review current totals, locate a member, and perform a global upload that caters for all transaction or balance data.

### Review overall totals

The statistics cards summarize the imported employee-balance data, including Carenderia, short-term loans, long-term loans, Consumer Balances, and total balances.

### Search for an employee/member

1. Enter a PMC ID or employee name in the search field at the top of the page.
2. Select **Search**.
3. Review the employee profile and balance details in the result window.

The result can include PMC ID, name, department, member status, member class, and balances for Carenderia, Consumer, and short-/long-term loans. Where a member class is configured, each balance is compared with its applicable limit and marked **GOOD** or **EXCEEDED**.

If no result is found, check the spelling and PMC ID, then confirm that the latest employee-balance data has been imported.

### Review upload history

The dashboard’s **Excel Upload History** lists recent uploaded files, the user who uploaded each file, and the upload time. Select **Download** to retrieve the original stored file.

This history is an important audit reference: it shows what was uploaded, when it was uploaded, and who uploaded it.

## 6. Import the dashboard employee-balance file

Use this workflow for the main employee/member balance report shown on the dashboard.

1. Make sure the source spreadsheet has been converted to a **CSV (Comma delimited)** file, as requested by the dashboard upload panel.
2. On the Dashboard, locate **Batch Upload Excel Data**.
3. Select the upload area or drag a file into it.
4. Confirm the selected filename.
5. Select **Process Batch Upload**.
6. Wait for the upload/processing message to finish. Do not refresh or close the page while the file is being processed.
7. Search for a known PMC ID to verify that the imported data appears correctly.

Supported file extensions are `.xlsx`, `.xls`, and `.csv`; the server limit is 10 MB. The importer expects the dashboard report layout, with one header row and the PMC ID in the third column. Do not upload an unrelated spreadsheet.

**Important:** This import replaces the existing dashboard employee-balance table before loading the new rows. Confirm that the file is complete and current before processing it.

## 7. Category-specific pages and uploads

The Carenderia, Loans, and Consumer Balances pages allow authorized users to update one specific category without replacing unrelated balance categories. Grocery and Payments use the same type of category page when enabled.

Use the Dashboard global upload when the complete employee/member balance report is being refreshed. Use a category-specific page when only one transaction or balance category needs to be updated.

### Upload a module file

1. Open the required module page.
2. In the batch import panel, select or drag the spreadsheet into the upload area.
3. Confirm the filename.
4. Select the module’s upload button.
5. Wait for the confirmation that the file has been uploaded and is processing.
6. Check the file list. The status may be **Processing**, **Completed**, or **Failed**.

Module upload files must be `.xlsx`, `.xls`, or `.csv`, and must not exceed 10 MB.

### File layouts

Use the layout supplied by the data owner. The current importer supports these formats:

| Use | Expected layout |
|---|---|
| Carenderia, Loans, Consumer Balances | Dashboard employee-balance report layout; one header row; PMC ID in column 3. Only the selected module’s balance fields are updated. |
| Grocery and Payments | Detail layout beginning on row 7; columns are employee ID, total, and date. |

For module imports, employee IDs are used to associate rows with employee records. Validate the employee ID, amount, and date columns before uploading.

### Filter and download upload history

Each category page provides:

- **Filename Search** to find an uploaded file;
- **Status** to show All States, Completed, Processing, or Failed;
- **From** and **To** dates to limit the upload date range;
- **Apply** to run the filter;
- **Download** to retrieve the original source file.

The category history records the uploaded filename, processing status, date/time, uploader, and row count where available. Use it to confirm when a category was updated and which account performed the upload.

If an upload is marked **Failed**, check the spreadsheet layout and data types, then contact the system administrator before retrying. Avoid repeatedly uploading the same file until the failure is understood.

## 8. User Management

Users with `manage_users` can open **User Management**.

### Create an administrator

1. Enter the person’s full name and email address.
2. Set and confirm a password of at least 8 characters.
3. Select only the permissions required for the person’s work:
   - **Upload files** (`upload_file`)
   - **Manage member classes** (`manage_member_classes`)
   - **Manage users** (`manage_users`)
4. Select **Create admin**.

Grant `manage_users` carefully because it allows the user to create administrators and change access permissions.

### Update administrator permissions

1. Find the administrator in the administrator list.
2. Select or clear the required permission checkboxes.
3. Select **Save access**.

Permission changes apply to that administrator’s available tools. A super admin remains unrestricted even if no individual permission boxes are selected.

### Employee directory

The User Management page also supports employee profiles with an employee code and name. If the employee directory tab is enabled, enter the employee code and full name, then select **Save employee profile**. Employee IDs should match the IDs used in uploaded files.

## 9. Member Classes

Users with `manage_member_classes` can configure the limits used in the dashboard’s balance checks.

### Create a member class

1. Open **Member Classes**.
2. Enter a unique class name, such as `GOLD` or `TITANIUM`.
3. Enter the **Carenderia Limit**, **Consumer Limit**, and **Maximum Loan** as non-negative amounts.
4. Select **Create Class**.

### Edit or delete a class

Edit the values directly in the configured-class table and select **Save**. Select **Delete** only when the class is no longer needed; confirm the deletion when prompted.

Keep class names consistent with the `mem_class` values in the employee-balance report. If a member’s class does not match a configured class, the dashboard cannot compare that member with configured limits and displays unavailable limit information.

## 10. Recommended operating procedure

For a regular reporting cycle:

1. Confirm the source files are complete and use the approved layouts.
2. Import the dashboard employee-balance report.
3. Import module data as required.
4. Review the upload status for each file.
5. Search several known PMC IDs and compare the displayed balances with the source report.
6. Investigate any **Failed** upload or unexpected balance before distributing results.
7. Download and retain the original uploaded files according to the organization’s records policy.

## 11. Troubleshooting

| Problem | What to check |
|---|---|
| Cannot sign in | Verify email/password, then ask a super admin to confirm the account exists and is active. |
| A menu item is missing | The account may lack the required permission, or the module may be hidden from the current navigation. |
| Upload rejected | Confirm the extension, file size, required layout, and that the file is not damaged. |
| Upload remains Processing | Refresh the module page after a short wait. If it remains unchanged, contact the system administrator to check the queue worker. |
| Upload is Failed | Confirm the expected row position, employee ID, amount, date, and numeric values; then review the application logs with an administrator. |
| Search returns no member | Search by the exact PMC ID, try the employee name, and verify that the latest employee-balance import completed. |
| Limits show N/A | Confirm that the employee’s `mem_class` exactly matches a configured Member Class. |
| Totals look incorrect | Check that the correct reporting period and complete source file were imported; do not upload a partial replacement file to the dashboard. |

## 12. Data and security notes

- Treat uploaded spreadsheets as confidential employee/member information.
- Do not share administrator credentials.
- Use the least privilege necessary when assigning permissions.
- Verify source data before importing because dashboard imports replace the existing employee-balance dataset.
- Log out after using a shared or public workstation.

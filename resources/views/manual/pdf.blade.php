<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PGECC User Manual</title>
    <style>
        @page { margin: 42px 48px; }
        body { font-family: DejaVu Sans, sans-serif; color: #1f2937; font-size: 10px; line-height: 1.55; }
        h1 { color: #0f172a; font-size: 24px; margin: 0 0 4px; }
        h2 { color: #1e3a8a; font-size: 15px; border-bottom: 1px solid #cbd5e1; padding-bottom: 4px; margin: 20px 0 8px; }
        h3 { color: #334155; font-size: 12px; margin: 12px 0 4px; }
        p { margin: 5px 0; }
        ul, ol { margin: 5px 0 8px 18px; padding: 0; }
        li { margin: 2px 0; }
        table { width: 100%; border-collapse: collapse; margin: 7px 0 10px; }
        th { background: #e2e8f0; color: #0f172a; text-align: left; }
        th, td { border: 1px solid #cbd5e1; padding: 5px; vertical-align: top; }
        .subtitle { color: #64748b; margin-bottom: 18px; }
        .note { background: #fff7ed; border-left: 4px solid #f97316; padding: 7px 9px; margin: 8px 0; }
        .footer { position: fixed; bottom: -25px; left: 0; right: 0; text-align: center; color: #94a3b8; font-size: 8px; }
        .page-break { page-break-before: always; }
    </style>
</head>
<body>
    <div class="footer">PGECC User Manual</div>
    <h1>PGECC User Manual</h1>
    <p class="subtitle"><strong>Audience:</strong> PGECC administrators and authorized data-entry users &nbsp;|&nbsp; <strong>Last reviewed:</strong> September 2026</p>

    <h2>1. Start here: administrator setup and access</h2>
    <p>PGECC access begins with a super administrator or authorized user manager. The administrator signs in, creates the user account, and assigns only the permissions required for that person’s work.</p>
    <h3>Create and authorize a user</h3>
    <ol>
        <li>Sign in using an administrator account and open <strong>User Management</strong>.</li>
        <li>Enter the new user’s name, email address, and password.</li>
        <li>Assign the required permissions: <code>upload_file</code>, <code>manage_member_classes</code>, and/or <code>manage_users</code>.</li>
        <li>Select <strong>Create admin</strong>, then tell the new user to sign in and change the temporary password.</li>
    </ol>
    <p>Assign the least access necessary. Grant <code>manage_users</code> only to people authorized to administer other accounts.</p>

    <h2>2. Purpose</h2>
    <p>PGECC is an internal administration system for reviewing employee/member balances and maintaining uploaded records for Carenderia, Loans, Consumer Balances, Grocery, and Payments.</p>
    <ul>
        <li>Search for an employee/member by PMC ID or name.</li>
        <li>Review balance limits and usage by member class.</li>
        <li>Import spreadsheet data and track processing status.</li>
        <li>Download original uploaded files.</li>
        <li>Manage administrator access and member-class limits.</li>
    </ul>

    <h2>3. Access and sign-in</h2>
    <ol>
        <li>Open the PGECC address supplied by your system administrator.</li>
        <li>Select <strong>Admin login</strong> if the sign-in window is not already open.</li>
        <li>Enter your email address and password, then select <strong>Sign in</strong>.</li>
    </ol>
    <h3>Change your password</h3>
    <ol>
        <li>Open the account menu at the bottom of the sidebar.</li>
        <li>Select <strong>Change password</strong>.</li>
        <li>Enter your current password, a new password, and confirmation.</li>
        <li>Select <strong>Update password</strong>. New passwords must contain at least 8 characters.</li>
    </ol>

    <h2>4. Navigation and permissions</h2>
    <table>
        <tr><th>Permission</th><th>Allows</th></tr>
        <tr><td><code>upload_file</code></td><td>Upload the dashboard employee-balance file and module files.</td></tr>
        <tr><td><code>manage_member_classes</code></td><td>Create, edit, and delete member classes and limits.</td></tr>
        <tr><td><code>manage_users</code></td><td>Create administrators and update administrator permissions.</td></tr>
    </table>
    <p>The super admin role has all permissions. The sidebar exposes Dashboard, Carenderia, Loans, Consumer Balances, User Management, Member Classes, and this manual. The User Manual button is located at the bottom of the sidebar above the account controls. Grocery and Payments routes exist but are currently hidden from the sidebar.</p>

    <h2>5. Dashboard and global data upload</h2>
    <p>The dashboard displays imported totals for Carenderia, short-term loans, long-term loans, Consumer Balances, and total balances. It also provides a global upload for the complete employee/member transaction or balance report.</p>
    <h3>Search for an employee/member</h3>
    <ol>
        <li>Enter a PMC ID or employee name in the search field.</li>
        <li>Select <strong>Search</strong>.</li>
        <li>Review the profile, member class, balances, limits, and status.</li>
    </ol>
    <p>Configured limits are compared with Carenderia, Consumer, and combined short-/long-term loan balances. A result is marked <strong>GOOD</strong> or <strong>EXCEEDED</strong>.</p>
    <h3>Upload history</h3>
    <p><strong>Excel Upload History</strong> lists recent files, uploader, and upload time. Select <strong>Download</strong> to retrieve the original file. This is an audit reference showing what was uploaded, when it was uploaded, and who uploaded it.</p>

    <h2>6. Import the dashboard employee-balance file</h2>
    <ol>
        <li>Convert the source report to <strong>CSV (Comma delimited)</strong>, as requested by the upload panel.</li>
        <li>On the Dashboard, locate <strong>Batch Upload Excel Data</strong>.</li>
        <li>Select the upload area or drag in the file.</li>
        <li>Confirm the filename and select <strong>Process Batch Upload</strong>.</li>
        <li>Wait for processing to finish, then search for a known PMC ID to verify the result.</li>
    </ol>
    <p>Supported extensions are .xlsx, .xls, and .csv. The server limit is 10 MB. The dashboard importer expects one header row and the PMC ID in the third column.</p>
    <div class="note"><strong>Important:</strong> This import replaces the existing dashboard employee-balance table before loading new rows. Use a complete, current file.</div>

    <div class="page-break"></div>
    <h2>7. Category-specific pages and uploads</h2>
    <p>Carenderia, Loans, and Consumer Balances allow authorized users to update one specific category without replacing unrelated balance categories. Grocery and Payments use the same page pattern when enabled.</p>
    <p>Use the Dashboard global upload for a complete report refresh. Use a category-specific page when only one transaction or balance category needs to be updated.</p>
    <ol>
        <li>Open the required module page.</li>
        <li>Select or drag a spreadsheet into the upload area.</li>
        <li>Confirm the filename and select the upload button.</li>
        <li>Check the file list for <strong>Processing</strong>, <strong>Completed</strong>, or <strong>Failed</strong>.</li>
    </ol>
    <table>
        <tr><th>Use</th><th>Expected layout</th></tr>
        <tr><td>Carenderia, Loans, Consumer Balances</td><td>Dashboard employee-balance layout; one header row; PMC ID in column 3. Only the selected module balance fields are updated.</td></tr>
        <tr><td>Grocery and Payments</td><td>Detail layout beginning on row 7; columns are employee ID, total, and date.</td></tr>
    </table>
    <p>Use <strong>Filename Search</strong>, <strong>Status</strong>, <strong>From</strong>, and <strong>To</strong> filters, then select <strong>Apply</strong>. Select <strong>Download</strong> to retrieve the original file. Category history records the filename, processing status, date/time, uploader, and row count where available.</p>

    <h2>8. User Management</h2>
    <p>Users with <code>manage_users</code> can create administrators, assign permissions, and maintain employee profiles.</p>
    <h3>Create an administrator</h3>
    <ol>
        <li>Enter the person’s name, email, and password.</li>
        <li>Select only the permissions required for the role.</li>
        <li>Select <strong>Create admin</strong>.</li>
    </ol>
    <p>Grant <code>manage_users</code> carefully because it permits creating administrators and changing access.</p>
    <h3>Update access</h3>
    <p>Find the administrator, select or clear the permission checkboxes, and select <strong>Save access</strong>. A super admin remains unrestricted.</p>

    <h2>9. Member Classes</h2>
    <p>Users with <code>manage_member_classes</code> configure limits used in dashboard balance checks.</p>
    <ol>
        <li>Open <strong>Member Classes</strong>.</li>
        <li>Enter a unique class name.</li>
        <li>Enter non-negative Carenderia, Consumer, and Maximum Loan limits.</li>
        <li>Select <strong>Create Class</strong>.</li>
    </ol>
    <p>Edit values in the configured-class table and select <strong>Save</strong>. Keep class names consistent with the <code>mem_class</code> values in the employee-balance report; otherwise limits display as unavailable.</p>

    <h2>10. Recommended operating procedure</h2>
    <ol>
        <li>Confirm that source files are complete and use the approved layouts.</li>
        <li>Import the dashboard employee-balance report.</li>
        <li>Import module data as required.</li>
        <li>Review each upload status.</li>
        <li>Search several known PMC IDs and compare results with the source report.</li>
        <li>Investigate failed uploads or unexpected balances before distributing results.</li>
    </ol>

    <h2>11. Troubleshooting</h2>
    <table>
        <tr><th>Problem</th><th>What to check</th></tr>
        <tr><td>Cannot sign in</td><td>Verify email/password and ask a super admin to confirm the account.</td></tr>
        <tr><td>Menu item is missing</td><td>Confirm the required permission or whether the module is hidden from navigation.</td></tr>
        <tr><td>Upload rejected</td><td>Check extension, 10 MB limit, file integrity, and required layout.</td></tr>
        <tr><td>Upload remains Processing</td><td>Refresh after a short wait. If unchanged, ask an administrator to check the queue worker.</td></tr>
        <tr><td>Upload Failed</td><td>Check row position, employee ID, amount, date, and numeric values.</td></tr>
        <tr><td>Search finds no member</td><td>Try the exact PMC ID and verify the latest employee-balance import completed.</td></tr>
        <tr><td>Limits show N/A</td><td>Match the employee’s member class to a configured Member Class.</td></tr>
    </table>

    <h2>12. Data and security</h2>
    <ul>
        <li>Treat uploaded spreadsheets as confidential employee/member information.</li>
        <li>Do not share administrator credentials.</li>
        <li>Use the least privilege necessary when assigning permissions.</li>
        <li>Verify source data before importing because dashboard imports replace the existing dataset.</li>
        <li>Log out after using a shared workstation.</li>
    </ul>
</body>
</html>

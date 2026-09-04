<?php

return [
    'leave' => [
        'submitted' => 'Absence request submitted.',
        'approved' => 'Absence request approved.',
        'rejected' => 'Absence request rejected.',
        'cancelled' => 'Absence request cancelled.',
        'overlap' => 'You already have a pending or approved request that overlaps these dates.',
        'exceeds_balance' => 'This request exceeds your remaining leave days (:remaining left).',
    ],
    'staff' => [
        'created' => 'Staff member created.',
        'updated' => 'Staff member updated.',
        'deleted' => 'Staff member deleted.',
        'imported' => ':count staff created. :skipped rows skipped.',
        'import_none' => 'No staff were created from the sheet.',
        'import_invalid' => 'Row :line is missing a valid name or email.',
        'import_duplicate' => 'Row :line email :email already exists.',
        'import_department' => 'Row :line department :department was not found.',
        'import_branch' => 'Row :line needs a department so the branch can be set.',
    ],
    'shift' => [
        'created' => 'Shift created.',
        'updated' => 'Shift updated.',
        'deleted' => 'Shift deleted.',
    ],
    'branch' => [
        'created' => 'Branch created.',
        'updated' => 'Branch updated.',
        'deleted' => 'Branch deleted.',
    ],
    'department' => [
        'created' => 'Department created.',
        'updated' => 'Department updated.',
        'deleted' => 'Department deleted.',
    ],
    'roster' => [
        'created' => 'Attendance session created.',
        'updated' => 'Attendance session updated.',
        'deleted' => 'Attendance session deleted.',
    ],
    'task' => [
        'created' => 'Task created.',
        'updated' => 'Task updated.',
        'deleted' => 'Task deleted.',
        'status' => 'Task status updated.',
        'commented' => 'Comment added.',
        'attached' => 'Attachment uploaded.',
    ],
    'attendance' => [
        'saved' => 'Attendance times saved.',
        'checked_in' => 'Checked in successfully.',
        'checked_out' => 'Checked out successfully.',
        'cleared' => 'Attendance records cleared.',
    ],
    'profile' => [
        'updated' => 'Profile updated.',
    ],
    'password' => [
        'updated' => 'Password updated.',
    ],
    'permissions' => [
        'updated' => 'Role permissions updated.',
        'role_created' => 'Role created.',
        'role_updated' => 'Role updated.',
        'role_deleted' => 'Role deleted.',
    ],
    'impersonation' => [
        'started' => 'You are now logged in as :name.',
        'stopped' => 'You are back as :name.',
    ],
];

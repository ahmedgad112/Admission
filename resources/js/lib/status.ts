export function humanize(value: string): string {
    return value.replaceAll('_', ' ');
}

export function attendanceTone(status: string): string {
    switch (status) {
        case 'present':
            return 'bg-emerald-100 text-emerald-800 dark:bg-emerald-400/15 dark:text-emerald-300';
        case 'late':
        case 'late_and_early_leave':
            return 'bg-amber-100 text-amber-800 dark:bg-amber-400/15 dark:text-amber-300';
        case 'early_leave':
            return 'bg-sky-100 text-sky-800 dark:bg-sky-400/15 dark:text-sky-300';
        case 'incomplete':
            return 'bg-slate-100 text-slate-700 dark:bg-slate-400/15 dark:text-slate-300';
        default:
            return 'bg-muted text-muted-foreground';
    }
}

export function taskStatusTone(status: string): string {
    switch (status) {
        case 'todo':
            return 'bg-slate-100 text-slate-700 dark:bg-slate-400/15 dark:text-slate-300';
        case 'in_progress':
            return 'bg-sky-100 text-sky-800 dark:bg-sky-400/15 dark:text-sky-300';
        case 'review':
            return 'bg-violet-100 text-violet-800 dark:bg-violet-400/15 dark:text-violet-300';
        case 'completed':
            return 'bg-emerald-100 text-emerald-800 dark:bg-emerald-400/15 dark:text-emerald-300';
        default:
            return 'bg-muted text-muted-foreground';
    }
}

export function userStatusTone(status: string): string {
    switch (status) {
        case 'active':
            return 'bg-emerald-100 text-emerald-800 dark:bg-emerald-400/15 dark:text-emerald-300';
        case 'inactive':
            return 'bg-slate-100 text-slate-700 dark:bg-slate-400/15 dark:text-slate-300';
        case 'suspended':
            return 'bg-rose-100 text-rose-800 dark:bg-rose-400/15 dark:text-rose-300';
        default:
            return 'bg-muted text-muted-foreground';
    }
}

export function userRoleTone(role: string): string {
    switch (role) {
        case 'super_admin':
            return 'bg-violet-100 text-violet-800 dark:bg-violet-400/15 dark:text-violet-300';
        case 'branch_admin':
            return 'bg-sky-100 text-sky-800 dark:bg-sky-400/15 dark:text-sky-300';
        case 'manager':
            return 'bg-amber-100 text-amber-800 dark:bg-amber-400/15 dark:text-amber-300';
        default:
            return 'bg-secondary text-secondary-foreground';
    }
}

export function leaveRequestStatusTone(status: string): string {
    switch (status) {
        case 'pending':
            return 'bg-amber-100 text-amber-800 dark:bg-amber-400/15 dark:text-amber-300';
        case 'approved':
            return 'bg-emerald-100 text-emerald-800 dark:bg-emerald-400/15 dark:text-emerald-300';
        case 'rejected':
            return 'bg-rose-100 text-rose-800 dark:bg-rose-400/15 dark:text-rose-300';
        case 'cancelled':
            return 'bg-slate-100 text-slate-700 dark:bg-slate-400/15 dark:text-slate-300';
        default:
            return 'bg-muted text-muted-foreground';
    }
}

export function taskPriorityTone(priority: string): string {
    switch (priority) {
        case 'low':
            return 'bg-secondary text-secondary-foreground';
        case 'medium':
            return 'bg-sky-100 text-sky-800 dark:bg-sky-400/15 dark:text-sky-300';
        case 'high':
            return 'bg-amber-100 text-amber-800 dark:bg-amber-400/15 dark:text-amber-300';
        case 'urgent':
            return 'bg-rose-100 text-rose-800 dark:bg-rose-400/15 dark:text-rose-300';
        default:
            return 'bg-muted text-muted-foreground';
    }
}

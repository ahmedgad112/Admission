<?php

use App\Enums\LeaveRequestType;
use App\Models\User;
use App\Support\AppLocale;
use Inertia\Testing\AssertableInertia as Assert;

test('guests can switch the site locale to arabic', function () {
    $this->from(route('home'))
        ->post(route('locale.update'), ['locale' => AppLocale::Arabic])
        ->assertRedirect(route('home'))
        ->assertPlainCookie('locale', AppLocale::Arabic);
});

test('authenticated users receive arabic translations after switching locale', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->withUnencryptedCookie('locale', AppLocale::Arabic)
        ->get(route('leave-requests.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('locale', AppLocale::Arabic)
            ->where('dir', 'rtl')
            ->has('translations')
        );

    expect(__('nav.leave'))->toBe('طلبات الغياب');
});

test('arabic leave request form shares translated field strings', function () {
    $user = User::factory()->employee()->create();

    $this->actingAs($user)
        ->withUnencryptedCookie('locale', AppLocale::Arabic)
        ->get(route('leave-requests.create'))
        ->assertOk()
        ->assertInertia(function (Assert $page) {
            $page->component('leave-requests/Create')
                ->where('locale', AppLocale::Arabic)
                ->where('dir', 'rtl')
                ->has('translations');

            /** @var array<string, string> $translations */
            $translations = $page->toArray()['props']['translations'];

            expect($translations['common.from'])->toBe('من')
                ->and($translations['common.to'])->toBe('إلى')
                ->and($translations['common.type'])->toBe('النوع')
                ->and($translations['common.reason'])->toBe('السبب')
                ->and($translations['leave.submit'])->toBe('إرسال الطلب')
                ->and($translations['leave.reason_placeholder'])->toBe('ليه محتاج تغيب؟')
                ->and($translations['leave.type.permission'])->toBe('إذن غياب');
        });
});

test('arabic dashboard shares rtl direction and sidebar navigation labels', function () {
    $user = User::factory()->superAdmin()->create();

    $this->actingAs($user)
        ->withUnencryptedCookie('locale', AppLocale::Arabic)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertInertia(function (Assert $page) {
            $page->component('Dashboard')
                ->where('locale', AppLocale::Arabic)
                ->where('dir', 'rtl')
                ->where('can.viewDashboard', true)
                ->has('translations');

            /** @var array<string, string> $translations */
            $translations = $page->toArray()['props']['translations'];

            expect($translations['nav.workspace'])->toBe('مساحة العمل')
                ->and($translations['nav.attendance'])->toBe('الحضور')
                ->and($translations['nav.organization'])->toBe('التنظيم')
                ->and($translations['nav.settings'])->toBe('الإعدادات')
                ->and($translations['nav.dashboard'])->toBe('لوحة التحكم');
        });
});

test('leave type labels follow the active locale', function () {
    app()->setLocale(AppLocale::Arabic);

    expect(LeaveRequestType::Permission->label())->toBe('إذن غياب');
});

test('task flash messages resolve from php language files', function () {
    expect(__('flash.task.created'))->toBe('Task created.')
        ->and(__('flash.task.updated'))->toBe('Task updated.')
        ->and(__('flash.task.deleted'))->toBe('Task deleted.')
        ->and(__('flash.task.status'))->toBe('Task status updated.')
        ->and(__('flash.task.commented'))->toBe('Comment added.')
        ->and(__('flash.task.attached'))->toBe('Attachment uploaded.');

    app()->setLocale(AppLocale::Arabic);

    expect(__('flash.task.created'))->toBe('تم إنشاء المهمة.');
});

test('invalid locales are rejected', function () {
    $this->from(route('home'))
        ->post(route('locale.update'), ['locale' => 'fr'])
        ->assertSessionHasErrors('locale');
});

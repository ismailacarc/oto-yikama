<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Income;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class GelirGider extends Component
{
    use WithPagination;

    public string $activeTab = 'ozet'; // ozet | giderler | gelirler | kategoriler

    // ── Dönem filtresi ─────────────────────────────────────────────
    public string $period   = 'month'; // today|week|month|year|custom
    public string $dateFrom = '';
    public string $dateTo   = '';

    // ── Gider formu ────────────────────────────────────────────────
    public bool   $showExpenseForm    = false;
    public ?int   $editingExpenseId   = null;
    public string $expTitle           = '';
    public ?int   $expCategoryId      = null;
    public string $expAmount          = '';
    public string $expDate            = '';
    public string $expSupplier        = '';
    public bool   $expIsRecurring     = false;
    public string $expRecurringPeriod = 'monthly';
    public string $expNote            = '';

    // Gider filtre
    public string $expSearch     = '';
    public string $expFilterCat  = '';

    // ── Gelir formu (manuel) ───────────────────────────────────────
    public bool   $showIncomeForm = false;
    public ?int   $editingIncomeId = null;
    public string $incTitle    = '';
    public string $incCategory = 'diger';
    public string $incAmount   = '';
    public string $incDate     = '';
    public string $incSource   = '';
    public string $incNote     = '';

    // Gelir filtre
    public string $incSearch     = '';
    public string $incFilterCat  = '';

    // ── Kategori yönetimi ──────────────────────────────────────────
    public bool   $showCatForm  = false;
    public ?int   $editingCatId = null;
    public string $catName      = '';
    public string $catColor     = 'slate';

    public function mount()
    {
        $this->dateFrom   = now()->startOfMonth()->format('Y-m-d');
        $this->dateTo     = now()->format('Y-m-d');
        $this->expDate    = now()->format('Y-m-d');
        $this->incDate    = now()->format('Y-m-d');
    }

    public function updatedPeriod()
    {
        match($this->period) {
            'today' => [
                $this->dateFrom = now()->format('Y-m-d'),
                $this->dateTo   = now()->format('Y-m-d'),
            ],
            'week'  => [
                $this->dateFrom = now()->startOfWeek()->format('Y-m-d'),
                $this->dateTo   = now()->endOfWeek()->format('Y-m-d'),
            ],
            'month' => [
                $this->dateFrom = now()->startOfMonth()->format('Y-m-d'),
                $this->dateTo   = now()->endOfMonth()->format('Y-m-d'),
            ],
            'year'  => [
                $this->dateFrom = now()->startOfYear()->format('Y-m-d'),
                $this->dateTo   = now()->endOfYear()->format('Y-m-d'),
            ],
            default => null,
        };
        $this->resetPage();
    }

    // ── Tab ────────────────────────────────────────────────────────
    public function updatingActiveTab() { $this->resetPage(); $this->closeAllForms(); }

    private function closeAllForms()
    {
        $this->showExpenseForm = false;
        $this->showIncomeForm  = false;
        $this->showCatForm     = false;
    }

    // ── Gider CRUD ─────────────────────────────────────────────────
    public function newExpense()
    {
        $this->resetExpForm();
        $this->showExpenseForm = true;
    }

    public function editExpense(int $id)
    {
        $e = Expense::findOrFail($id);
        $this->editingExpenseId   = $id;
        $this->expTitle           = $e->title;
        $this->expCategoryId      = $e->expense_category_id;
        $this->expAmount          = (string) $e->amount;
        $this->expDate            = $e->expense_date->format('Y-m-d');
        $this->expSupplier        = $e->supplier ?? '';
        $this->expIsRecurring     = $e->is_recurring;
        $this->expRecurringPeriod = $e->recurring_period ?? 'monthly';
        $this->expNote            = $e->note ?? '';
        $this->showExpenseForm    = true;
    }

    public function saveExpense()
    {
        $this->validate([
            'expTitle'      => 'required|string|max:200',
            'expCategoryId' => 'required|exists:expense_categories,id',
            'expAmount'     => 'required|numeric|min:0.01',
            'expDate'       => 'required|date',
        ], [
            'expTitle.required'      => 'Başlık giriniz.',
            'expCategoryId.required' => 'Kategori seçiniz.',
            'expAmount.required'     => 'Tutar giriniz.',
            'expDate.required'       => 'Tarih seçiniz.',
        ]);

        $data = [
            'title'               => $this->expTitle,
            'expense_category_id' => $this->expCategoryId,
            'amount'              => (float) $this->expAmount,
            'expense_date'        => $this->expDate,
            'supplier'            => $this->expSupplier ?: null,
            'is_recurring'        => $this->expIsRecurring,
            'recurring_period'    => $this->expIsRecurring ? $this->expRecurringPeriod : null,
            'note'                => $this->expNote ?: null,
        ];

        if ($this->editingExpenseId) {
            Expense::findOrFail($this->editingExpenseId)->update($data);
        } else {
            Expense::create($data);
        }

        $this->resetExpForm();
        $this->showExpenseForm = false;
    }

    public function deleteExpense(int $id)
    {
        Expense::findOrFail($id)->delete();
    }

    private function resetExpForm()
    {
        $this->editingExpenseId   = null;
        $this->expTitle           = '';
        $this->expCategoryId      = null;
        $this->expAmount          = '';
        $this->expDate            = now()->format('Y-m-d');
        $this->expSupplier        = '';
        $this->expIsRecurring     = false;
        $this->expRecurringPeriod = 'monthly';
        $this->expNote            = '';
    }

    // ── Gelir CRUD ─────────────────────────────────────────────────
    public function newIncome()
    {
        $this->resetIncForm();
        $this->showIncomeForm = true;
    }

    public function editIncome(int $id)
    {
        $inc = Income::findOrFail($id);
        $this->editingIncomeId = $id;
        $this->incTitle    = $inc->title;
        $this->incCategory = $inc->category;
        $this->incAmount   = (string) $inc->amount;
        $this->incDate     = $inc->income_date->format('Y-m-d');
        $this->incSource   = $inc->source ?? '';
        $this->incNote     = $inc->note ?? '';
        $this->showIncomeForm = true;
    }

    public function saveIncome()
    {
        $this->validate([
            'incTitle'  => 'required|string|max:200',
            'incAmount' => 'required|numeric|min:0.01',
            'incDate'   => 'required|date',
        ], [
            'incTitle.required'  => 'Başlık giriniz.',
            'incAmount.required' => 'Tutar giriniz.',
            'incDate.required'   => 'Tarih seçiniz.',
        ]);

        $data = [
            'title'       => $this->incTitle,
            'category'    => $this->incCategory,
            'amount'      => (float) $this->incAmount,
            'income_date' => $this->incDate,
            'source'      => $this->incSource ?: null,
            'note'        => $this->incNote ?: null,
        ];

        if ($this->editingIncomeId) {
            Income::findOrFail($this->editingIncomeId)->update($data);
        } else {
            Income::create($data);
        }

        $this->resetIncForm();
        $this->showIncomeForm = false;
    }

    public function deleteIncome(int $id)
    {
        Income::findOrFail($id)->delete();
    }

    private function resetIncForm()
    {
        $this->editingIncomeId = null;
        $this->incTitle    = '';
        $this->incCategory = 'diger';
        $this->incAmount   = '';
        $this->incDate     = now()->format('Y-m-d');
        $this->incSource   = '';
        $this->incNote     = '';
    }

    // ── Kategori CRUD ──────────────────────────────────────────────
    public function newCategory()
    {
        $this->editingCatId = null;
        $this->catName  = '';
        $this->catColor = 'slate';
        $this->showCatForm = true;
    }

    public function editCategory(int $id)
    {
        $cat = ExpenseCategory::findOrFail($id);
        $this->editingCatId = $id;
        $this->catName  = $cat->name;
        $this->catColor = $cat->color;
        $this->showCatForm = true;
    }

    public function saveCategory()
    {
        $this->validate([
            'catName' => 'required|string|max:100',
        ], ['catName.required' => 'Kategori adı giriniz.']);

        $data = ['name' => $this->catName, 'color' => $this->catColor, 'is_active' => true];

        if ($this->editingCatId) {
            ExpenseCategory::findOrFail($this->editingCatId)->update($data);
        } else {
            ExpenseCategory::create($data);
        }

        $this->showCatForm = false;
        $this->catName = '';
        $this->catColor = 'slate';
        $this->editingCatId = null;
    }

    public function toggleCategory(int $id)
    {
        $cat = ExpenseCategory::findOrFail($id);
        $cat->update(['is_active' => !$cat->is_active]);
    }

    public function deleteCategory(int $id)
    {
        $cat = ExpenseCategory::findOrFail($id);
        if ($cat->expenses()->count() === 0) {
            $cat->delete();
        }
    }

    // ── Render ─────────────────────────────────────────────────────
    public function render()
    {
        $from = Carbon::parse($this->dateFrom)->startOfDay();
        $to   = Carbon::parse($this->dateTo)->endOfDay();

        // ── Özet verileri ──────────────────────────────────────
        // Gelir = Payment (iş emri tahsilatı) + Income (manuel)
        $paymentIncome = Payment::whereBetween('paid_at', [$from, $to])->sum('amount');
        $manualIncome  = Income::whereBetween('income_date', [$from->toDateString(), $to->toDateString()])->sum('amount');
        $totalIncome   = $paymentIncome + $manualIncome;

        $totalExpense  = Expense::whereBetween('expense_date', [$from->toDateString(), $to->toDateString()])->sum('amount');
        $netProfit     = $totalIncome - $totalExpense;
        $margin        = $totalIncome > 0 ? round(($netProfit / $totalIncome) * 100, 1) : 0;

        // Kategoriye göre gider dağılımı (özet için)
        $expByCat = Expense::with('expenseCategory')
            ->whereBetween('expense_date', [$from->toDateString(), $to->toDateString()])
            ->select('expense_category_id', DB::raw('SUM(amount) as total'))
            ->groupBy('expense_category_id')
            ->orderByDesc('total')
            ->get();

        // Aylık trend (son 6 ay)
        $trend = [];
        for ($i = 5; $i >= 0; $i--) {
            $m     = now()->subMonths($i);
            $mFrom = $m->copy()->startOfMonth()->toDateString();
            $mTo   = $m->copy()->endOfMonth()->toDateString();
            $inc   = Payment::whereBetween('paid_at', [$mFrom.' 00:00:00', $mTo.' 23:59:59'])->sum('amount')
                   + Income::whereBetween('income_date', [$mFrom, $mTo])->sum('amount');
            $exp   = Expense::whereBetween('expense_date', [$mFrom, $mTo])->sum('amount');
            $trend[] = ['label' => $m->translatedFormat('M'), 'income' => (float)$inc, 'expense' => (float)$exp];
        }
        $trendMax = max(array_merge(array_column($trend,'income'), array_column($trend,'expense'), [1]));

        // ── Giderler listesi ────────────────────────────────────
        $expQuery = Expense::with('expenseCategory')
            ->whereBetween('expense_date', [$from->toDateString(), $to->toDateString()])
            ->when($this->expSearch, fn($q) =>
                $q->where('title','like','%'.$this->expSearch.'%')
                  ->orWhere('supplier','like','%'.$this->expSearch.'%')
            )
            ->when($this->expFilterCat, fn($q) => $q->where('expense_category_id', $this->expFilterCat))
            ->orderBy('expense_date','desc');

        $expenses     = $this->activeTab === 'giderler' ? $expQuery->paginate(20) : collect();
        $expTotal     = $expQuery->sum('amount');

        // Tekrarlayan giderler (tümü, dönemden bağımsız)
        $recurringExp = Expense::with('expenseCategory')
            ->where('is_recurring', true)
            ->orderBy('expense_date','desc')
            ->get();

        // ── Gelirler listesi ────────────────────────────────────
        // Manuel gelirler
        $incQuery = Income::whereBetween('income_date', [$from->toDateString(), $to->toDateString()])
            ->when($this->incSearch, fn($q) =>
                $q->where('title','like','%'.$this->incSearch.'%')
                  ->orWhere('source','like','%'.$this->incSearch.'%')
            )
            ->when($this->incFilterCat, fn($q) => $q->where('category', $this->incFilterCat))
            ->orderBy('income_date','desc');

        $manualIncomes = $this->activeTab === 'gelirler' ? $incQuery->paginate(15) : collect();

        // İş emri tahsilatları (bilgi amaçlı)
        $payQuery = Payment::with(['workOrder','customer'])
            ->whereBetween('paid_at', [$from, $to])
            ->when($this->incSearch, fn($q) =>
                $q->whereHas('customer', fn($c) => $c->where('name','like','%'.$this->incSearch.'%'))
            )
            ->orderBy('paid_at','desc');

        $payments = $this->activeTab === 'gelirler' ? $payQuery->paginate(15, ['*'], 'pay_page') : collect();

        // ── Kategoriler ────────────────────────────────────────
        $categories = ExpenseCategory::withCount('expenses')->orderBy('name')->get();

        return view('livewire.admin.gelir-gider', compact(
            'totalIncome','paymentIncome','manualIncome',
            'totalExpense','netProfit','margin',
            'expByCat','trend','trendMax',
            'expenses','expTotal','recurringExp',
            'manualIncomes','payments',
            'categories',
        ))->layout('components.layouts.admin');
    }
}

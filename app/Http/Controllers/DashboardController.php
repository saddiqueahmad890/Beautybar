<?php

namespace App\Http\Controllers;

use App\Models\ExamInvestigation;
use App\Models\HospitalDepartment;
use App\Models\Inventory;
use App\Models\Invoice;
use App\Models\PatientAppointment;
use App\Models\PatientCaseStudy;
use App\Models\PatientTreatmentPlan;
use App\Models\Payment;
use App\Models\Prescription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class DashboardController
 *
 * @package App\Http\Controllers
 * @category Controller
 */
class DashboardController extends Controller
{
    /**
     * Display a listing of the resource with optional date filtering.
     *
     * @access public
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // If a date range is provided, filter data accordingly
        if ($startDate && $endDate) {
            $dashboardCounts = $this->getFilteredDashboardCounts($startDate, $endDate);
        } else {
            $dashboardCounts = $this->dashboardCounts();
        }

        if (auth()->user()->hasRole('Super Admin') || auth()->user()->hasRole('Admin')) {
            return $this->adminDashboard($dashboardCounts);
        } elseif (auth()->user()->hasRole('Doctor')) {
            $doctorId = auth()->user()->id;
            $patientAppointment = PatientAppointment::with('user')
                ->where('doctor_id', $doctorId)
                ->where('company_id', session('company_id'))
                ->get();
            return view('dashboards.common-dashboard', compact('patientAppointment', 'dashboardCounts'));
        } elseif (auth()->user()->hasRole('Patient')) {
            $patientId = auth()->user()->id;
            $appointments = PatientAppointment::with('user')
                ->where('user_id', $patientId)
                ->where('company_id', session('company_id'))
                ->get();
            return view('dashboards.common-dashboard', compact('appointments', 'dashboardCounts'));
        } elseif (auth()->user()->hasRole('Receptionist')) {
            $receptionistAppointments = PatientAppointment::with('user')
                ->where('company_id', session('company_id'))
                ->get();
            return view('dashboards.common-dashboard', compact('receptionistAppointments', 'dashboardCounts'));
        } else {
            return view('dashboards.common-dashboard', compact('dashboardCounts'));
        }
    }

    /**
     * Shows the admin dashboard.
     *
     * @param array $dashboardCounts
     * @return \Illuminate\Http\Response
     */
    private function adminDashboard($dashboardCounts)
    {
        $monthlyDebitCredit = $this->monthlyDebitCredit();
        $currentYearDebitCredit = $this->currentYearDebitCredit();
        $overallDebitCredit = $this->overallDebitCredit();

        return view('dashboardview', compact('dashboardCounts', 'monthlyDebitCredit', 'currentYearDebitCredit', 'overallDebitCredit'));
    }

    /**
     * Gets the filtered dashboard counts based on date range.
     *
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    private function getFilteredDashboardCounts($startDate, $endDate)
    {
        return [
            'departments' => HospitalDepartment::whereBetween('created_at', [$startDate, $endDate])->count(),
            'exam_investigation' => ExamInvestigation::whereBetween('created_at', [$startDate, $endDate])->count(),
            'treatment_plans' => PatientTreatmentPlan::whereBetween('created_at', [$startDate, $endDate])->count(),
            'inventory' => Inventory::whereBetween('created_at', [$startDate, $endDate])->sum('purchased_qty'),
            'inventorystk' => Inventory::whereBetween('created_at', [$startDate, $endDate])->sum('quantity'),
            'doctors' => User::role('Doctor')->whereBetween('created_at', [$startDate, $endDate])->count(),
            'patients' => User::role('Patient')->whereBetween('created_at', [$startDate, $endDate])->count(),
            'appointments' => PatientAppointment::whereBetween('created_at', [$startDate, $endDate])->count(),
            'caseStudies' => PatientCaseStudy::whereBetween('created_at', [$startDate, $endDate])->count(),
            'prescriptions' => Prescription::whereBetween('created_at', [$startDate, $endDate])->count(),
            'invoices' => Invoice::whereBetween('created_at', [$startDate, $endDate])->count(),
            'payments' => Payment::whereBetween('created_at', [$startDate, $endDate])->count(),
            'total' => Invoice::whereBetween('created_at', [$startDate, $endDate])->sum('total'),
            'inventorySum' => Inventory::whereBetween('created_at', [$startDate, $endDate])->sum(DB::raw('unit_cost * purchased_qty')),
            'paid' => Invoice::whereBetween('created_at', [$startDate, $endDate])->sum('paid'),
            'totalAmount' => Payment::whereBetween('created_at', [$startDate, $endDate])->sum('amount')
        ];
    }

    /**
     * Gets the default dashboard counts without any filtering.
     *
     * @return array
     */
    private function dashboardCounts()
    {
        return cache()->remember('dashboardCounts', 600, function () {
            return [
                'departments' => HospitalDepartment::count(),
                'exam_investigation' => ExamInvestigation::count(),
                'treatment_plans' => PatientTreatmentPlan::count(),
                'inventory' => Inventory::sum('purchased_qty'),
                'inventorystk' => Inventory::sum('quantity'),
                'doctors' => User::role('Doctor')->count(),
                'patients' => User::role('Patient')->count(),
                'appointments' => PatientAppointment::count(),
                'caseStudies' => PatientCaseStudy::count(),
                'prescriptions' => Prescription::count(),
                'invoices' => Invoice::count(),
                'payments' => Payment::count(),
                'total' => Invoice::sum('total'),
                'inventorySum' => Inventory::sum(DB::raw('unit_cost * purchased_qty')),
                'paid' => Invoice::sum('paid'),
                'totalAmount' => Payment::sum('amount')
            ];
        });
    }

    /**
     * Gets monthly debit/credit sums for bar chart.
     *
     * @return array
     */
    private function monthlyDebitCredit()
    {
        return cache()->remember('monthlyDebitCredit', 600, function () {
            $credits = [];
            $debits = [];
            $labels = [];
            $results = DB::select('SELECT DISTINCT YEAR(invoice_date) AS "year", MONTH(invoice_date) AS "month" FROM invoices ORDER BY year DESC LIMIT 12');
            foreach ($results as $result) {
                $labels[] = '"' . date('F', mktime(0, 0, 0, $result->month, 10)) . ' ' . $result->year . '"';
                $credits[] = '"' . Payment::whereYear('payment_date', $result->year)->whereMonth('payment_date', $result->month)->sum('amount') . '"';
                $debits[] = '"' . Invoice::whereYear('invoice_date', $result->year)->whereMonth('invoice_date', $result->month)->sum('grand_total') . '"';
            }

            return [
                'credits' => $credits,
                'debits' => $debits,
                'labels' => $labels
            ];
        });
    }

    /**
     * Gets current year debit/credit sums for pie chart.
     *
     * @return array
     */
    private function currentYearDebitCredit()
    {
        return cache()->remember('currentYearDebitCredit', 600, function () {
            $credits = Payment::whereYear('payment_date', date('Y'))->sum('amount');
            $debits = Invoice::whereYear('invoice_date', date('Y'))->sum('grand_total');

            return [
                'credits' => $credits,
                'debits' => $debits
            ];
        });
    }

    /**
     * Gets overall debit/credit sums for pie chart.
     *
     * @return array
     */
    private function overallDebitCredit()
    {
        return cache()->remember('overallDebitCredit', 600, function () {
            $credits = Payment::sum('amount');
            $debits = Invoice::sum('grand_total');

            return [
                'credits' => $credits,
                'debits' => $debits
            ];
        });
    }

    /**
     * Retrieves chart data for the dashboard.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getChartData()
    {
        return response()->json([
            'monthlyDebitCredit' => $this->monthlyDebitCredit(),
            'currentYearDebitCredit' => $this->currentYearDebitCredit(),
            'overallDebitCredit' => $this->overallDebitCredit()
        ], 200);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Csmlbusi;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Fetch total sales for only won bids
        $totalSales = Csmlbusi::where('bid_status', 'won')->sum('amount'); // Only sales where bid_status is 'won'

        // Count tenders, quotations, and prequalifications won (still checking for 'won' bids)
        $tendersWon = Csmlbusi::where('business_type', 'tender')->where('bid_status', 'won')->count();
        $quotationsWon = Csmlbusi::where('business_type', 'quotation')->where('bid_status', 'won')->count();
        $prequalificationsWon = Csmlbusi::where('business_type', 'prequalification')->where('bid_status', 'won')->count();

        // Data for the sales chart (last 12 months - only 'won' bids)
        $salesData = Csmlbusi::where('bid_status', 'won') // Ensure we are only selecting 'won' sales
            ->whereYear('created_at', date('Y')) // Only for the current year
            ->selectRaw('MONTH(created_at) as month, SUM(amount) as total_sales')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total_sales'); // Get sales data for each month

        // Ensure all months are accounted for, even those with no sales
        $salesData = array_pad($salesData->toArray(), 12, 0); // Pad with 0 for missing months if necessary

        // Data for the category chart (won bids only)
        $categoryLabels = ['Tenders', 'Quotations', 'Prequalifications'];
        $categoryData = [
            Csmlbusi::where('business_type', 'tender')->where('bid_status', 'won')->sum('amount'), // Summing the amounts for won tenders
            Csmlbusi::where('business_type', 'quotation')->where('bid_status', 'won')->sum('amount'), // Summing the amounts for won quotations
            Csmlbusi::where('business_type', 'prequalification')->where('bid_status', 'won')->sum('amount') // Summing the amounts for won prequalifications
        ];

        // Fetch recent won sales (last 5 sales)
        $recentSales = Csmlbusi::where('bid_status', 'won') // Only fetch won sales
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Month labels for the graph
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        return view('admin.dashboard', compact(
            'totalSales', 
            'tendersWon', 
            'quotationsWon', 
            'prequalificationsWon', 
            'months', 
            'salesData', 
            'categoryLabels', 
            'categoryData', 
            'recentSales'
        ));
    }
}

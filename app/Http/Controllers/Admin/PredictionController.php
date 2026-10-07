<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Prediction;
use Illuminate\Http\Request;
use Illuminate\View\View;
class PredictionController extends Controller
{
    public function index(Request $request): View
    {
        $query = Prediction::with(['user','disease','feedback'])->latest();
        if ($request->filled('q')) $query->where('predicted_class','like','%'.$request->q.'%');
        return view('admin.predictions.index',['predictions'=>$query->paginate(25)->withQueryString()]);
    }
}

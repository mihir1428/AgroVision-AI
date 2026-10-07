<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Disease;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
class DiseaseController extends Controller
{
    public function index(): View { return view('admin.diseases.index',['diseases'=>Disease::orderBy('crop_name')->orderBy('disease_name')->paginate(20)]); }
    public function create(): View { return view('admin.diseases.form',['disease'=>new Disease()]); }
    public function store(Request $request): RedirectResponse { Disease::create($this->validated($request)); return redirect()->route('admin.diseases.index')->with('success','Disease added.'); }
    public function edit(Disease $disease): View { return view('admin.diseases.form',compact('disease')); }
    public function update(Request $request, Disease $disease): RedirectResponse { $disease->update($this->validated($request,$disease->id)); return redirect()->route('admin.diseases.index')->with('success','Disease updated.'); }
    public function destroy(Disease $disease): RedirectResponse { $disease->update(['is_active'=>false]); return back()->with('success','Disease disabled.'); }
    private function validated(Request $request, ?int $id=null): array
    {
        return $request->validate([
            'class_key'=>['required','string','max:190','unique:diseases,class_key'.($id?','.$id:'')],
            'crop_name'=>['required','string','max:100'],'disease_name'=>['required','string','max:150'],'scientific_name'=>['nullable','string','max:190'],
            'description'=>['required','string'],'symptoms'=>['required','string'],'cause'=>['nullable','string'],'prevention'=>['required','string'],'management'=>['required','string'],
            'bangla_description'=>['nullable','string'],'bangla_symptoms'=>['nullable','string'],'bangla_management'=>['nullable','string'],'is_active'=>['nullable','boolean'],
        ]) + ['is_active'=>$request->boolean('is_active')];
    }
}

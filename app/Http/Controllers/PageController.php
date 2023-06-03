<?php

namespace App\Http\Controllers;

use App\Models\Comments;
use App\Models\Products;
use App\Models\Slides;
use App\Models\TypeProducts;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function getIndex()
    {
        $slide = Slides::all();
        $new_product = Products::where('new', 1)->paginate(8);
        $sanpham_khuyenmai = Products::where('promotion_price', '<>', 0)->paginate(4);
        return view('page.trangchu', compact('slide', 'new_product', 'sanpham_khuyenmai'));
    }

    public function getDetail(Request $request)
    {
        $sanpham = Products::where('id', $request->id)->first();
        $splienquan = Products::where('id', '<>', $sanpham->id)
            ->where('id_type', $sanpham->id_type)
            ->paginate(3);
        $comments = Comments::where('id_product', $request->id)->get();
        return view('page.chitiet_sanpham', compact('sanpham', 'splienquan', 'comments'));
    }

    public function getLoaiSp($type)
    {
        $type_product = TypeProducts::all();
        $sp_theoloai = Products::where('id_type', $type)->get();
        $sp_khac = Products::where('id_type', '<>', $type)->paginate(3);
        return view('page.loai_sanpham', compact('sp_theoloai', 'type_product', 'sp_khac'));
    }
    public function getIndexAdmin()
    {
        $products = product::all();
        return view('pageadmin.admin')->with(['product' => $products, 'sumSold' => count(bill_detail::all())]);
    }

    public function getAdminAdd()
    {
        return view('pageadmin.formAdd');
    }
    public function postAdminAdd(Request $request)
    {
        $product = new product();
        if ($request->hasFile('inputImage')) {
            $file = $request->file('inputImage');
            $fileName = $file->getClientOriginalName('inputImage');
            $file->move('source/image/product', $fileName);
        }
        $file_name = null;
        if ($request->file('inputImage') != null) {
            $file_name = $request->file('inputImage')->getClientOriginalName();
        }

        $product->name = $request->inputName;
        $product->image = $file_name;
        $product->description = $request->inputDescription;
        $product->unit_price = $request->inputPrice;
        $product->promotion_price = $request->inputPromotionPrice;
        $product->unit = $request->inputUnit;
        $product->new = $request->inputNew;
        $product->id_type = $request->inputType;
        $product->save();
        return redirect('/admin');
    }

    public function getAdminEdit($id)
    {
        $product = product::find($id);
        return view('pageadmin.formEdit')->with('product', $product);
    }

    public function postAdminEdit(Request $request)
    {
        $id = $request->editId;
        $product =  product::find($id);
        if ($request->hasFile('editImage')) {
            $file = $request->file('editImage');
            $fileName = $file->getClientOriginalName();
            $file->move('source/image/product', $fileName);
        }
        if ($request->file('editImage') != null) {
            $product->image =$fileName;
        }

        $product->name = $request->editName;
        $product->description = $request->editDescription;
        $product->unit_price = $request->editPrice;
        $product->promotion_price = $request->editPromotionPrice;
        $product->unit = $request->editUnit;
        $product->new = $request->editNew;
        $product->id_type = $request->editType;
        $product->save();
        return redirect('/admin');
    }
    public function postAdminDelete($id)
    {
      $product = product::find($id);
      $product->delete();
      return $this->getIndexAdmin();  
    }
}

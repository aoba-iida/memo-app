<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Memo; 

class MemoController extends Controller
{
    // ① 一覧取得のロジック (GET)
    public function index()
    {
        // 全件取得して返す
        $memos = Memo::all();
        return response()->json($memos);
    }

    // ② 新規保存のロジック (POST)
    public function store(Request $request)
    {
        // 新しいメモを用意
        $memo = new Memo();
        
        // フロントから送られてきた 'content' をセット
        $memo->content = $request->input('content');
        
        // データベースに保存！
        $memo->save();

        // 成功した証として、保存したデータを返す
        return response()->json($memo);
    }

    // ③ 削除のロジック (DELETE)
    public function destroy($id)
    {
        // 指定されたIDのメモを探す
        $memo = Memo::find($id);
        
        // 削除実行！
        $memo->delete();

        // 成功メッセージを返す
        return response()->json(['message' => 'Deleted successfully']);
    }
}
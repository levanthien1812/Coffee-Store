<?php

namespace App\Http\Controllers;
use App\Models\BlogParagraph;
use App\Models\Paragraph;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class Blog extends Controller
{
    function getAllBlogs(){
        $blogsDB = DB::table('blogs')->get();

        return response()->json([
            'message' => 'Get All Blogs',
            'data' => $blogsDB
        ]);
    }

        /**
     * Get Blog
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  unsignedBigInteger  $id
     * @return \Illuminate\Http\Response
     */

    function getBlogByID(Request $request, $id ) {
        $blogParas = [];
        $blogsDB = DB::table('blogs')->where('id', $id)->get();
        $blogParagraphDB = DB::table('blog_paragraphs')->where('BlogID', $id)->get();
       
        for ($i=0; $i < count($blogParagraphDB); $i++) { 
            $para = DB::table('paragraphs')->where('BlogParagraphID', $blogParagraphDB[$i]->id)->get();
            $blogParaRes = json_encode($blogParagraphDB[$i]);
            $blogParaRes = json_decode($blogParaRes, true);
            $blogParaRes['Paragraphs'] = $para;
            array_push($blogParas, $blogParaRes);
        }

        $blogDB = json_encode($blogsDB[0]);
        $blogDB = json_decode($blogDB, true);
        $blogDB['Content'] = $blogParas;
        
        return response()->json([
            'message' => 'Get Detail Blog',
            'data' => $blogDB
        ]);
    }
}

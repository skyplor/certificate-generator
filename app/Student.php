<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = ['name','phone1','email','phone2','address','verification_doc_url','id_proof','age','gender','fathersname','photo_url','start_date','end_date','social1_name','social1_url','social2_name','social2_url','social3_name','social3_url','created_at','updated_at'];
}

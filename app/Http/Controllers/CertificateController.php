<?php

namespace App\Http\Controllers;

use App\Imports\StudentsImport;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index(){
        $certificates = \DB::table('certificates')
        ->join('students','students.id','=','student_id')
        ->join('courses','courses.id','=','course_id')
        ->select('courses.name as course_name','certificates.id','students.name as student_name','students.phone1 as student_phone','certificates.certification_number','certificates.rating','students.start_date','students.end_date' )
        ->get();
        
        return view('certificate.index',compact('certificates'));
    }

    public function viewBulk(){
        $courses = \App\Course::all();
        return view('certificate.viewbulk',compact('courses'));
    }

    public function postBulk(Request $request){
        $course_id = $request->course_id;

        $file = $request->file('excel_file');
        $excel_url = time().'.'.$file->getClientOriginalExtension();
        $destinationPath = public_path('/tmp');
        $file->move($destinationPath, $excel_url);

        $fullPath = $destinationPath.'/'.$excel_url;

        $data = new \App\Certificate;
        $certis = array();

        $students_array = (new StudentsImport)->toArray($fullPath)[0];

        foreach ($students_array as $row) {
            $student_email = $row['email'];
            $student = \App\Student::whereEmail($student_email)->firstOrFail();
            $row += ['student_id' => $student->id]; 
            $row += ['course_id' => $course_id];
            $generator = new RandomStringGenerator;
            $tokenLength = 10;
            $token = $generator->generate($tokenLength);
            $row += ['certification_number' => $token];
            \App\Certificate::Create($row);
            $certis[] = $row;
        }

        return view('certificate.bulkcerti',compact('certis'));

    }

    public function create(){
        $students = \App\Student::all();
        $courses = \App\Course::all();
        return view('certificate.create',compact('students','courses'));
    }

    public function postCreate(Request $request){
        $generator = new RandomStringGenerator;
        $tokenLength = 10;
        $token = $generator->generate($tokenLength);
        
        $request->request->add(['certification_number' => $token]);

        \App\Certificate::create($request->all());

        return redirect('/admin/certificate')->with('status', 'Created Successfully');
    }

    public function edit($id){
        $students = \App\Student::all();
        $courses = \App\Course::all();
        $certificate = \App\Certificate::find($id);
        return view('certificate.edit',compact('certificate','students','courses'));
    }

    public function show($id){
        $certificate = \DB::table('certificates')
        ->join('students','students.id','=','student_id')
        ->join('courses','courses.id','=','course_id')
        ->select('courses.name as course_name','certificates.id as certificate_id', 'students.photo_url as student_photo_url', 'certificates.id as id','students.name as student_name','students.phone1 as student_phone','certificates.certification_number','certificates.rating','students.start_date','students.end_date','courses.venue' )
        ->where('certificates.id','=',$id)
        ->first();

        return view('certificate.show',compact('certificate'));
    }

    public function postEdit(Request $request){
        \App\Certificate::find($request->id)->update($request->all());

        return redirect('/admin/certificate')->with('status', 'Updated Successfully');
    }

    public function delete($id){
        \App\Certificate::destroy($id);

        return redirect('/admin/certificate')->with('status', 'Deleted Successfully');
    }
}

class RandomStringGenerator
{
    /** @var string */
    protected $alphabet;

    /** @var int */
    protected $alphabetLength;


    /**
     * @param string $alphabet
     */
    public function __construct($alphabet = '')
    {
        if ('' !== $alphabet) {
            $this->setAlphabet($alphabet);
        } else {
            $this->setAlphabet(
                  implode(range('a', 'z'))
                . implode(range('A', 'Z'))
                . implode(range(0, 9))
            );
        }
    }

    /**
     * @param string $alphabet
     */
    public function setAlphabet($alphabet)
    {
        $this->alphabet = $alphabet;
        $this->alphabetLength = strlen($alphabet);
    }

    /**
     * @param int $length
     * @return string
     */
    public function generate($length)
    {
        $token = '';

        for ($i = 0; $i < $length; $i++) {
            $randomKey = $this->getRandomInteger(0, $this->alphabetLength);
            $token .= $this->alphabet[$randomKey];
        }

        return strtoupper($token);
    }

    /**
     * @param int $min
     * @param int $max
     * @return int
     */
    protected function getRandomInteger($min, $max)
    {
        $range = ($max - $min);

        if ($range < 0) {
            // Not so random...
            return $min;
        }

        $log = log($range, 2);

        // Length in bytes.
        $bytes = (int) ($log / 8) + 1;

        // Length in bits.
        $bits = (int) $log + 1;

        // Set all lower bits to 1.
        $filter = (int) (1 << $bits) - 1;

        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));

            // Discard irrelevant bits.
            $rnd = $rnd & $filter;

        } while ($rnd >= $range);

        return ($min + $rnd);
    }
}

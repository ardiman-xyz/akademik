<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use DB;

class KrsMatakuliahDibukaController extends Controller
{
  public function getOpenedCourse($Nim, $Term_Year_Id)
  {
    $Student = DB::table('acd_student')->where('Nim',$Nim)->first();

    $data = DB::select(DB::raw("  select* from
        (  select  distinct  oc.Course_Id as ID_MK,  c.Course_Code as Kode_MK, c.Course_Name as Nama_MK, c.Course_Name_Eng as Nama_MK_English,
        km.Applied_Sks as SKS, ccs.amount_per_sks*km.APPLIED_SKS as Harga, ctk.course_type_id as Kelompok
        from acd_offered_course oc
        inner join acd_curriculum_entry_year ka
        on ka.Class_Prog_Id=oc.Class_Prog_Id
and ka.Entry_Year_Id=  $Student->Entry_Year_Id
        and ka.Term_Year_Id=oc.Term_Year_Id
        inner join acd_course_curriculum km
        on km.Department_Id=oc.Department_Id
and km.Class_Prog_Id=oc.Class_Prog_Id
and km.Curriculum_Id=ka.Curriculum_Id
and km.Course_Id=oc.course_id
        inner join acd_course c
        on c.DEPARTMENT_ID=oc.department_id
and c.course_id=oc.course_id
        left join fnc_course_type ctk
on ctk.course_Type_id = c.Course_Type_Id
        inner join fnc_course_cost_package ccm
        on ccm.Entry_Year_Id=SUBSTR(oc.Term_Year_Id, 1, LENGTH(oc.Term_Year_Id) - 1)
        inner join fnc_course_cost_sks ccs
        on ccs.Term_Year_Id=oc.Term_Year_Id
and oc.Department_Id=ccs.Department_Id
        and oc.Class_Prog_Id = ccs.Class_Prog_Id
and ccs.Entry_Year_Id=ka.Entry_Year_Id
        and ccs.amount_per_sks is not null
        where  oc.Term_Year_Id=   $Term_Year_Id
and oc.Department_Id=  $Student->Department_Id
and oc.Class_Prog_Id=  $Student->Class_Prog_Id
        UNION
        select  distinct oc.Course_Id as ID_MK, c.Course_Code as Kode_MK, c.Course_Name as Nama_MK, c.Course_Name_Eng as Corse_Name_Eng,
        km.APPLIED_SKS as SKS, ccm.amount_per_mk as Harga, ctk.course_type_id as Kelompok
        from acd_offered_course oc
        inner join acd_curriculum_entry_year ka
        on ka.Department_Id=oc.Department_Id
and ka.CLASS_PROG_ID=oc.class_Prog_id
and ka.Entry_Year_Id=  2016
        and ka.Term_Year_Id=oc.Term_Year_Id
inner join acd_course_curriculum km
        on km.Department_Id=oc.Department_Id
and km.CLASS_PROG_ID=oc.class_Prog_id
and km.Curriculum_Id=ka.Curriculum_Id
and km.Course_Id=oc.course_id
        inner join acd_course c
        on c.DEPARTMENT_ID=oc.department_id and c.course_id=oc.course_id
        left join fnc_course_type ctk
        on ctk.course_id = oc.course_id
        inner join fnc_course_cost_package ccm
        on ccm.Entry_Year_Id=SUBSTR(oc.Term_Year_Id, 1, LENGTH(oc.Term_Year_Id) - 1)
        and ccm.entry_year_Id=ka.Entry_Year_Id
        and ccm.amount_per_mk is not null
            where  oc.Term_Year_Id=  $Term_Year_Id
and oc.Department_Id= $Student->Department_Id
and oc.Class_Prog_Id=  $Student->Class_Prog_Id
        UNION
        select oc.Course_Id as ID_MK, c.Course_Code as Kode_MK, c.Course_Name as Nama_MK, c.Course_Name_Eng as Nama_MK_English,
        km.APPLIED_SKS as SKS, 0 as Harga, ctk.course_type_id as Kelompok
from acd_offered_course oc
        inner join acd_course c
        on c.DEPARTMENT_ID=oc.department_id and c.course_id=oc.course_id
        left join fnc_course_type ctk
on ctk.course_id = oc.course_id
        inner join acd_curriculum_entry_year ka
        on ka.Entry_Year_Id = SUBSTR(oc.Term_Year_Id, 1, LENGTH(oc.Term_Year_Id) - 1)
and  ka.department_id=oc.department_id and
        ka.class_prog_id=oc.class_prog_id
and ka.Entry_Year_Id =  $Student->Entry_Year_Id
        inner join acd_course_curriculum km
        on km.department_id=ka.department_id and
        km.class_prog_id=ka.class_prog_id and
        km.Curriculum_Id=ka.Curriculum_Id and
        km.course_id=oc.course_id
        where oc.Term_Year_Id=  $Term_Year_Id
                              and oc.Department_Id= $Student->Department_Id
                              and oc.Class_Prog_Id=  $Student->Class_Prog_Id
                                        ) as temp
                                        where temp.ID_MK not in
                                        (select course_id from acd_student_krs sck
                                         where sck.Term_Year_Id =   $Term_Year_Id
                                         and sck.Student_Id =  $Student->Student_Id
                                         )
                                        ORDER BY temp.Kode_MK"));
    return ($data);
  }

  public function getOpenedCourseAllYear($Nim, $Term_Year_Id)
  {
    $Student = DB::table('acd_student')->where('Nim',$Nim)->first();

    $data = DB::select(DB::raw('SELECT c.Course_Id as ID_MK,
                       c.Course_Code as Kode_MK,
                       c.Course_Name as Nama_MK,
                       c.Course_Name_Eng is null as Nama_MK_English,
                       ck.Transcript_Sks as SKS,
                       cc.Amount_Per_Sks*ck.Transcript_Sks as Harga,
                       cg.Name_Of_Group as Kelompok
                          FROM fnc_course_cost_sks cc
                            JOIN acd_course c  on c.department_id = cc.department_id
                            join acd_course_curriculum ck on  ck.Course_Id=c.Course_Id and c.Department_Id=ck.Department_Id
                            join acd_course_group cg on cg.Course_Group_Id=ck.Course_Group_Id
                                    WHERE cc.Term_Year_Id =    '.$Term_Year_Id.'
                                    AND cc.Department_Id =   '.$Student->Department_Id.'
                                    AND cc.class_prog_id =   '.$Student->Class_Prog_Id.'
                                    AND cc.Entry_Year_Id =   '.$Student->Entry_Year_Id.'
                                    AND ck.course_id not in (select course_id from acd_student_krs sck
                                                              where sck.Term_Year_Id =   '.$Term_Year_Id.'
                                                              and sck.student_id =  '.$Student->Student_Id.' )
                                    AND cc.Amount_Per_Sks is not NULL
                                    AND exists
                                    (select temp2.Course_Id from
                                        (select cid1.Course_Id
                                            from acd_course_identic_detail cid1
                                              join acd_course_identic ci on cid1.Course_Identic_Id = ci.Course_Identic_Id
                                              where ci.Department_Id=(select Department_Id from acd_student WHERE Student_Id = '.$Student->Student_Id.')
                                                and exists (
                                                    select cid2.Course_Identic_Id from acd_course_identic_detail cid2
                                                    join acd_course_identic ci2 on cid2.Course_Identic_Id=ci2.Course_Identic_Id
                                                    where ci2.Department_Id=ci.Department_Id
                                                      and cid2.Course_Identic_Id=cid1.Course_Identic_Id
                                                      and cid2.Course_Id in ( select distinct temp1.Course_Id from
                                                                                 ( select sck.Course_Id from acd_student_krs sck
                                                                                   where sck.Student_Id= '.$Student->Student_Id.'
                                                                                   union
                                                                                   select krs.Course_Id
                                                                                      from acd_student_khs sk
                                                                                      join acd_student_krs krs on krs.Krs_Id=sk.Krs_Id
                                                                                   where sk.Student_Id= '.$Student->Student_Id.' ) as temp1)
                                                           )
                                            union
                                              select sck2.Course_Id from acd_student_krs sck2
                                                where sck2.Student_Id= '.$Student->Student_Id.'
                                            union
                                              select krs2.Course_Id from acd_student_khs sk2
                                              join acd_student_krs krs2 on krs2.krs_Id=sk2.Krs_Id
                                              where sk2.Student_Id='.$Student->Student_Id.'
                                              /* union
                                            select ket.COURSE_ID_BARU from KURIKULUM_EKUIVALENSI_TRANSKRIP ket
                                            where ket.studentid=m.studentid */
                                        ) as temp2
                                      where temp2.Course_Id = ck.Course_Id
                                    )
                                    Order by c.Course_Code'));
    return ($data);
  }


  public function getOpenedCourseAllYearYbs($Nim, $Term_Year_Id)
  {
    $Student = DB::table('acd_student')->where('Nim',$Nim)->first();
    $year = DB::table('mstr_term_year')->where('Term_Year_Id', $Term_Year_Id);

    $data = DB::select(DB::raw('SELECT c.Course_Id as ID_MK,
                       c.Course_Code as Kode_MK,
                       c.Course_Name as Nama_MK,
                       c.Course_Name_Eng is null as Nama_MK_English,
                       ck.Transcript_Sks as SKS,
                       cc.Amount_Per_Sks*ck.Transcript_Sks as Harga,
                       cg.Name_Of_Group as Kelompok
                          FROM fnc_course_cost_sks cc
                            JOIN acd_course c  on c.department_id = cc.department_id
                            join acd_course_curriculum ck on  ck.Course_Id=c.Course_Id and c.Department_Id=ck.Department_Id
                            join acd_course_group cg on cg.Course_Group_Id=ck.Course_Group_Id
                                    WHERE cc.Term_Year_Id =    '.$Term_Year_Id.'
                                    AND cc.Department_Id =   '.$tudent->Department_Id.'
                                    AND cc.class_prog_id =  '.$Student->Class_Prog_Id.'
                                    AND cc.Entry_Year_Id =   '.$Student->Entry_Year_Id.'
                                    AND ck.course_id not in (select course_id from acd_student_krs sck
                                                              where sck.Term_Year_Id =   '.$Term_Year_Id.'
                                                              and sck.student_id =  '.$Student->Student_Id.' )
                                    AND cc.Amount_Per_Sks is not NULL
                                    AND exists
                                    (select temp2.Course_Id from
                                        (select cid1.Course_Id
                                            from acd_course_identic_detail cid1
                                              join acd_course_identic ci on cid1.Course_Identic_Id = ci.Course_Identic_Id
                                              where ci.Department_Id=(select Department_Id from acd_student WHERE Student_Id = '.$Student->Student_Id.')
                                                and exists (
                                                    select cid2.Course_Identic_Id from acd_course_identic_detail cid2
                                                    join acd_course_identic ci2 on cid2.Course_Identic_Id=ci2.Course_Identic_Id
                                                    where ci2.Department_Id=ci.Department_Id
                                                      and cid2.Course_Identic_Id=cid1.Course_Identic_Id
                                                      and cid2.Course_Id in ( select distinct temp1.Course_Id from
                                                                                 ( select sck.Course_Id from acd_student_krs sck
                                                                                   where sck.Student_Id= '.$Student->Student_Id.'
                                                                                   union
                                                                                   select krs.Course_Id
                                                                                      from acd_student_khs sk
                                                                                      join acd_student_krs krs on krs.Krs_Id=sk.Krs_Id
                                                                                   where sk.Student_Id= '.$Student->Student_Id.' ) as temp1)
                                                           )
                                            union
                                              select sck2.Course_Id from acd_student_krs sck2
																							inner join mstr_term_year mty on mty.Term_Year_Id = sck2.Term_Year_Id
                       	                 and mty.Year_Id=2017
                                                where sck2.Student_Id= '.$Student->Student_Id.'
                                            union
                                              select krs2.Course_Id from acd_student_khs sk2
                                              join acd_student_krs krs2 on krs2.krs_Id=sk2.Krs_Id
																							inner join mstr_term_year mty2 on mty2.Term_Year_Id = krs2.Term_Year_Id
                       	                 and mty2.Year_Id='.$Student->Entry_Year_Id.'
                                              where sk2.Student_Id='.$Student->Student_Id.'
                                              /* union
                                            select ket.COURSE_ID_BARU from KURIKULUM_EKUIVALENSI_TRANSKRIP ket
                                            where ket.studentid=m.studentid */
                                        ) as temp2
                                      where temp2.Course_Id = ck.Course_Id
                                    )
                                    Order by c.Course_Code'));
return ($data);
  }


  public function getOpenedCourseNilaiAllYear($Nim, $Term_Year_Id, $grade)
  {
    $Student = DB::table('acd_student')->where('Nim',$Nim)->first();
    $data = DB::select(DB::raw('SELECT c.Course_Id as ID_MK,
                       c.Course_Code as Kode_MK,
                       c.Course_Name as Nama_MK,
                       c.Course_Name_Eng is null as Nama_MK_English,
                       ck.Transcript_Sks as SKS,
                       cc.Amount_Per_Sks*ck.Transcript_Sks as Harga,
                       cg.Name_Of_Group as Kelompok
                          FROM fnc_course_cost_sks cc
                            JOIN acd_course c  on c.department_id = cc.department_id
                            join acd_course_curriculum ck on  ck.Course_Id=c.Course_Id and c.Department_Id=ck.Department_Id
                            join acd_course_group cg on cg.Course_Group_Id=ck.Course_Group_Id
                                    WHERE cc.Term_Year_Id =    '.$Term_Year_Id.'
                                    AND cc.Department_Id =   '.$Student->Department_Id.'
                                    AND cc.class_prog_id =   '.$Student->Class_Prog_Id.'
                                    AND cc.Entry_Year_Id =   '.$Student->Entry_Year_Id.'
                                    AND ck.course_id not in (select course_id from acd_student_krs sck
                                                              where sck.Term_Year_Id =   '.$Term_Year_Id.'
                                                              and sck.student_id =  '.$Student->Student_Id.' )
                                    AND cc.Amount_Per_Sks is not NULL
                                    AND exists
                                    (select temp2.Course_Id from
                                        (select cid1.Course_Id
                                            from acd_course_identic_detail cid1
                                              join acd_course_identic ci on cid1.Course_Identic_Id = ci.Course_Identic_Id
                                              where ci.Department_Id=(select Department_Id from acd_student WHERE Student_Id = '.$Student->Student_Id.')
                                                and exists (
                                                    select cid2.Course_Identic_Id from acd_course_identic_detail cid2
                                                    join acd_course_identic ci2 on cid2.Course_Identic_Id=ci2.Course_Identic_Id
                                                    where ci2.Department_Id=ci.Department_Id
                                                      and cid2.Course_Identic_Id=cid1.Course_Identic_Id
                                                      and cid2.Course_Id in ( select distinct temp1.Course_Id from
                                                                                 ( select sck.Course_Id from acd_student_krs sck
                                                                                   where sck.Student_Id= '.$Student->Student_Id.'
                                                                                   union
                                                                                   select krs.Course_Id
                                                                                      from acd_student_khs sk
                                                                                      join acd_student_krs krs on krs.Krs_Id=sk.Krs_Id
                                                                                   where sk.Student_Id= '.$Student->Student_Id.' ) as temp1)
                                                           )
                                            union
                                              select krs2.Course_Id from acd_student_khs sk2
                                              join acd_student_krs krs2 on krs2.krs_Id=sk2.Krs_Id
                                              where sk2.Student_Id='.$Student->Student_Id.'
																							and sk2.Grade_Letter_Id <=  '.$grade.'
                                              /* union
                                            select ket.COURSE_ID_BARU from KURIKULUM_EKUIVALENSI_TRANSKRIP ket
                                            where ket.studentid=m.studentid */
                                        ) as temp2
                                      where temp2.Course_Id = ck.Course_Id
                                    )
                                    Order by c.Course_Code'));

    return ($data);
  }


  public function getOpenedCourseNilaiAllYearYbs($Nim, $Term_Year_Id,$grade)
  {
    $Student = DB::table('acd_student')->where('Nim',$Nim)->first();
    $data = DB::select(DB::raw('SELECT c.Course_Id as ID_MK,
                       c.Course_Code as Kode_MK,
                       c.Course_Name as Nama_MK,
                       c.Course_Name_Eng is null as Nama_MK_English,
                       ck.Transcript_Sks as SKS,
                       cc.Amount_Per_Sks*ck.Transcript_Sks as Harga,
                       cg.Name_Of_Group as Kelompok
                          FROM fnc_course_cost_sks cc
                            JOIN acd_course c  on c.department_id = cc.department_id
                            join acd_course_curriculum ck on  ck.Course_Id=c.Course_Id and c.Department_Id=ck.Department_Id
                            join acd_course_group cg on cg.Course_Group_Id=ck.Course_Group_Id
                                    WHERE cc.Term_Year_Id =   '.$Term_Year_Id.'
                                    AND cc.Department_Id =   '.$Student->Department_Id.'
                                    AND cc.class_prog_id =   '.$Student->Class_Prog_Id.'
                                    AND cc.Entry_Year_Id =   '.$Student->Entry_Year_Id.'
                                    AND ck.course_id not in (select course_id from acd_student_krs sck
                                                              where sck.Term_Year_Id =   '.$Term_Year_Id.'
                                                              and sck.student_id =  '.$Student->Student_Id.' )
                                    AND cc.Amount_Per_Sks is not NULL
                                    AND exists
                                    (select temp2.Course_Id from
                                        (select cid1.Course_Id
                                            from acd_course_identic_detail cid1
                                              join acd_course_identic ci on cid1.Course_Identic_Id = ci.Course_Identic_Id
                                              where ci.Department_Id=(select Department_Id from acd_student WHERE Student_Id = '.$Student->Student_Id.')
                                                and exists (
                                                    select cid2.Course_Identic_Id from acd_course_identic_detail cid2
                                                    join acd_course_identic ci2 on cid2.Course_Identic_Id=ci2.Course_Identic_Id
                                                    where ci2.Department_Id=ci.Department_Id
                                                      and cid2.Course_Identic_Id=cid1.Course_Identic_Id
                                                      and cid2.Course_Id in ( select distinct temp1.Course_Id from
                                                                                 ( select sck.Course_Id from acd_student_krs sck
                                                                                   where sck.Student_Id= '.$Student->Student_Id.'
                                                                                   union
                                                                                   select krs.Course_Id
                                                                                      from acd_student_khs sk
                                                                                      join acd_student_krs krs on krs.Krs_Id=sk.Krs_Id
                                                                                   where sk.Student_Id= '.$Student->Student_Id.' ) as temp1)
                                                           )
                                            union
                                              select krs2.Course_Id from acd_student_khs sk2
                                              join acd_student_krs krs2 on krs2.krs_Id=sk2.Krs_Id
																							join mstr_term_year mty on krs2.Term_Year_Id=mty.Term_Year_Id
                                              where sk2.Student_Id='.$Student->Student_Id.'
																							and mty.Year_Id= '.$Student->Entry_Year_Id.'
																							and sk2.Grade_Letter_Id<= '.$grade.'
                                              /* union
                                            select ket.COURSE_ID_BARU from KURIKULUM_EKUIVALENSI_TRANSKRIP ket
                                            where ket.studentid=m.studentid */
                                        ) as temp2
                                      where temp2.Course_Id = ck.Course_Id
                                    )
                                    Order by c.Course_Code'));
    return ($data);
  }
}

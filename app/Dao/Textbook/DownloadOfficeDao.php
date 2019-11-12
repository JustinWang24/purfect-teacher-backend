<?php
namespace App\Dao\Textbook;

use Carbon\Carbon;
use App\Dao\Schools\GradeDao;
use App\Dao\Schools\CampusDao;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class DownloadOfficeDao
{


     /**
      * 校区教材数据
     * @param $data
     * @return Spreadsheet
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function campusDataDispose($data) {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('校区教材采购');
        $sheet->setCellValue('A1', '课程名称');
        $sheet->setCellValue('B1', '课程编号');
        $sheet->setCellValue('C1', '学分');
        $sheet->setCellValue('D1', '年级');
        $sheet->setCellValue('E1', '教材名称');
        $sheet->setCellValue('F1', '出版社');
        $sheet->setCellValue('G1', '作者');
        $sheet->setCellValue('H1', '购买数量');
        $sheet->setCellValue('I1', '计划统招人数');
        $sheet->setCellValue('J1', '计划自招人数');
        $sheet->setCellValue('K1', '计划总共招生人数');
        $sheet->setCellValue('L1', '统招录取人数');
        $sheet->setCellValue('M1', '自招录取人数');
        $sheet->setCellValue('N1', '总共录取人数');
        $num =  2;
        foreach ($data as $key => $val) {

            $sheet->setCellValue('A'.$num, $val['course']['name']);
            $sheet->setCellValue('B'.$num, $val['course']['code']);
            $sheet->setCellValue('C'.$num, $val['course']['scores']);
            $sheet->setCellValue('D'.$num, $val['course']['year']);
            if(!empty($val['course']['course_textbooks'])){

                foreach ($val['course']['course_textbooks'] as $k => $v) {
                    $num = $num + $k;
                    $sheet->setCellValue('E'.$num, $v['textbook']['name']);
                    $sheet->setCellValue('F'.$num, $v['textbook']['press']);
                    $sheet->setCellValue('G'.$num, $v['textbook']['author']);

                    if($val['type'] == 0) {
                        $sheet->setCellValue('H'.$num, $val['textbook_num']);
                    } else {
                        $sheet->setCellValue('I'.$num, $val['textbook_num']['general_plan_seat']);
                        $sheet->setCellValue('J'.$num, $val['textbook_num']['self_plan_seat']);
                        $sheet->setCellValue('K'.$num, $val['textbook_num']['total_plan_seat']);
                        $sheet->setCellValue('L'.$num, $val['textbook_num']['general_informatics_seat']);
                        $sheet->setCellValue('M'.$num, $val['textbook_num']['self_informatics_seat']);
                        $sheet->setCellValue('N'.$num, $val['textbook_num']['total_informatics_seat']);
                    }
                }
                $num = $num +1;
            }
        }
        return $spreadsheet;
    }


    /**
     * 校区教材下载
     * @param $campusId
     * @param $type
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function campusDownload($campusId, $type) {

        $textbookDao = new TextbookDao();
        $result = $textbookDao->getCampusTextbook($campusId);
        if(!$result->isSuccess()) {
            return $result;
        }
        $data = $result->getData();


        $spreadsheet = $this->campusDataDispose($data);
        $campusDao = new CampusDao();
        $info = $campusDao->getCampusById($campusId);

        $title = $info['name'].'教材采购';
        if($type == 'excel') {
            $writer = new Xlsx($spreadsheet);
            $filename = Carbon::now()->toDateString().$title.'.xlsx';
        } elseif($type == 'csv') {
            $writer = new Csv($spreadsheet);
            $filename = Carbon::now()->toDateString().$title.'.csv';
        }

        header("Content-Type:application/application/vnd.ms-excel");
        header("Content-Type:application/download");
        header('Content-Disposition:attachment;filename='.$filename);
        $writer->save('php://output');
    }


    /**
     * @param $data
     * @return Spreadsheet
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function gradeDataDispose($data) {

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('校区教材采购');

        $sheet->setCellValue('A1', '课程名称');
        $sheet->setCellValue('B1', '课程编号');
        $sheet->setCellValue('C1', '学分');
        $sheet->setCellValue('D1', '年级');
        $sheet->setCellValue('E1', '教材名称');
        $sheet->setCellValue('F1', '出版社');
        $sheet->setCellValue('G1', '作者');
        $sheet->setCellValue('H1', '购买数量');

        $num =  2;
        foreach ($data as $key => $val) {

            $sheet->setCellValue('A'.$num, $val['name']);
            $sheet->setCellValue('B'.$num, $val['code']);
            $sheet->setCellValue('C'.$num, $val['scores']);
            $sheet->setCellValue('D'.$num, $val['year']);
            if(!empty($val['textbooks'])){

                foreach ($val['course_textbooks'] as $k => $v) {
                    $num = $num + $k;
                    $sheet->setCellValue('E'.$num, $v['textbook']['name']);
                    $sheet->setCellValue('F'.$num, $v['textbook']['press']);
                    $sheet->setCellValue('G'.$num, $v['textbook']['author']);
                    $sheet->setCellValue('H'.$num, $val['textbook_num']);

                }
                $num = $num +1;
            }
        }

        return $spreadsheet;
    }



    /**
     * 下载班级教材
     * @param $gradeId
     * @param $type
     * @return \App\Utils\ReturnData\MessageBag
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function gradeDownload($gradeId, $type) {

        $textbookDao = new TextbookDao();
        $result = $textbookDao->getTextbooksByGradeId($gradeId);
        if(!$result->isSuccess()) {
            return $result;
        }
        $data = $result->getData();
        $spreadsheet = $this->gradeDataDispose($data);
        $gradeDao = new GradeDao();
        $info = $gradeDao->getGradeById($gradeId);

        $title = $info->name.'教材采购';

        if($type == 'excel') {
            $writer = new Xlsx($spreadsheet);
            $filename = Carbon::now()->toDateString().$title.'.xlsx';
        } elseif($type == 'csv') {
            $writer = new Csv($spreadsheet);
            $filename = Carbon::now()->toDateString().$title.'.csv';
        }

        header("Content-Type:application/application/vnd.ms-excel");
        header("Content-Type:application/download");
        header('Content-Disposition:attachment;filename='.$filename);
        $writer->save('php://output');
    }


}

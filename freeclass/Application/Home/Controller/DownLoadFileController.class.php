<?php
/**
 * Created by PhpStorm.
 * User: 龙鲤
 * Date: 2016/12/10
 * Time: 13:00
 */

namespace Home\Controller;


use Think\Controller;
vendor('PHPExcelUnit.PHPExcel');   //引入PHPExcel类库
vendor('PHPWord.PHPWord');   //引入PHPWord类库
class DownLoadFileController extends BaseController
{
    public function exportExcel($expTitle,$expCellName,$expTableData){
        $xlsTitle = iconv('utf-8', 'gb2312', $expTitle);//文件名称
        $fileName = $_SESSION['account'].date('_YmdHis');//or $xlsTitle 文件名称可根据自己情况设定
        $cellNum = count($expCellName);
        $dataNum = count($expTableData);
        vendor("PHPExcel.PHPExcel");

        $objPHPExcel = new \PHPExcel();
        $cellName = array('A','B','C');//这个自己可以改

        $objPHPExcel->getActiveSheet(0)->mergeCells('A1:'.$cellName[$cellNum-1].'1');//合并单元格
        // $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $expTitle.'  Export time:'.date('Y-m-d H:i:s'));
        for($i=0;$i<$cellNum;$i++){
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$i].'2', $expCellName[$i][1]);
        }
        // Miscellaneous glyphs, UTF-8
        for($i=0;$i<$dataNum;$i++){
            for($j=0;$j<$cellNum;$j++){
                $objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j].($i+3), $expTableData[$i][$expCellName[$j][0]]);
            }
        }
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save("./Public/download/$fileName.xls");
        echo "<script language='javascript'>";
        echo " window.location.href= '/freeclass/Public/download/$fileName.xls';";
        echo "</script>";
    }
    /**
     *
     * 导出Excel，以下信息都可以自己定义
     */
    function expUser($Group,$xlsData){//导出Excel
        $xlsName  = $Group.'成员空课情况';
        //将数据库的字段与excel里的列相对应起来
        $xlsCell  = array(
            array('id','学号'),
            array('name','名字'),
            array('freeclass','空课'),
        );
        $this->exportExcel($xlsName,$xlsCell,$xlsData);
    }

    /**
     * 生成word文件
     */
    public function exportWord($name){
        // New Word Document
        $PHPWord = new \PHPWord();

        // New portrait section
        $section = $PHPWord->createSection();
        $PHPWord->addFontStyle('rStyle', array('bold'=>true, 'italic'=>true, 'size'=>16));
        $PHPWord->addParagraphStyle('pStyle', array('align'=>'center', 'spaceAfter'=>100));
        $section->addText(iconv('utf-8', 'GB2312//IGNORE', '空课统计结果'),'rStyle','pStyle');
        //定义样式数组
        $styleTable = array(
            'borderSize'=>6,
            'borderColor'=>'006699',
            'cellMargin'=>80
        );
        $styleFirstRow = array(
            'borderBottomSize'=>18,
            'borderBottomColor'=>'0000ff',
            'bgColor'=>'66bbff'
        );

        //定义单元格样式数组
        $styleCell = array('valign'=>'center');
        $styleCellBTLR = array('valign'=>'center','textDirection'=>\PHPWord_Style_Cell::TEXT_DIR_BTLR);

        //定义第一行的字体
        $fontStyle = array('bold'=>true,'align'=>'center');

        //添加表格样式
        $PHPWord->addTableStyle('myOwnTableStyle',$styleTable,$styleFirstRow);

        //添加表格
        $table = $section->addTable('myOwnTableStyle');

        $table->addRow();
        $table->addCell(1750)->addText("");
        $table->addCell(1750)->addText(iconv('utf-8', 'GB2312//IGNORE', '星期一'));
        $table->addCell(1750)->addText(iconv('utf-8', 'GB2312//IGNORE', '星期二'));
        $table->addCell(1750)->addText(iconv('utf-8', 'GB2312//IGNORE', '星期三'));
        $table->addCell(1750)->addText(iconv('utf-8', 'GB2312//IGNORE', '星期四'));
        $table->addCell(1750)->addText(iconv('utf-8', 'GB2312//IGNORE', '星期五'));
        $table->addCell(1750)->addText(iconv('utf-8', 'GB2312//IGNORE', '星期六'));
        $table->addCell(1750)->addText(iconv('utf-8', 'GB2312//IGNORE', '星期日'));
        for($r = 1; $r <= 5; $r++) { // Loop through rows
            // Add row
            $table->addRow();
            $table->addCell(1750)->addText(iconv('utf-8', 'GB2312//IGNORE', '第'.$r.'节'));

            for($c = 1; $c <= 7; $c++) { // Loop through cells
                // Add Cell
                $table->addCell(1500)->addText(iconv('utf-8', 'GB2312//IGNORE', $name[($c-1)*5+$r]));
            }
        }

        // Save File
        $fileName = date("YmdHis");
        $objWriter = \PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
        $objWriter->save("./Public/download/$fileName.docx");
        echo "<script language='javascript'>";
        echo " window.location.href= '/freeclass/Public/download/$fileName.docx';";
        echo "</script>";
    }
}
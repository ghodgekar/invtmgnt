import { Component, ElementRef, OnInit, ViewChild } from '@angular/core';
import { AbstractControl, FormBuilder, FormGroup, Validators } from '@angular/forms';

import jsPDF from 'jspdf';
import * as pdfMake from 'pdfmake/build/pdfmake.js';
import * as pdfFonts from 'pdfmake/build/vfs_fonts.js';
(pdfMake as any).vfs = pdfFonts.pdfMake.vfs;

import * as XLSX from 'xlsx';
import htmlToPdfmake from 'html-to-pdfmake';
import { Subject } from 'rxjs';
import { OpningStockService } from 'src/app/services/transactions/opning-stock.service';
import { CommonListService } from 'src/app/services/master/common-list.service';
import { CommonService } from 'src/app/services/common.service';

@Component({
  selector: 'app-opning-stock',
  templateUrl: './opning-stock.component.html',
  styleUrls: ['./opning-stock.component.css']
})
export class OpningStockComponent {

  public view: any = 'opening-stock';
  branchForm!: FormGroup;
  submitted: boolean = false;
  data:any=[];
  parent_menu: any=[];
  submitBtn:String ='SAVE';

  @ViewChild('pdfTable')
  pdfTable!: ElementRef;

  dtOptions: DataTables.Settings = {};
  dtTrigger: Subject<any> = new Subject();
  departmentData: any;

  constructor(private fb: FormBuilder, private OpeningStockHttp:OpningStockService, private commonListHttp: CommonListService, private common: CommonService) {
    this.createForm();
  }
  
  createForm() {
    this.branchForm = this.fb.group({
      loc_code: ['KMTH'],
      barcode: [''],
      item_code: [''],
      item_type: [''],
      qty: [''],
      mrp: [''],
      sale_rate: [''],
      cost_rate: [''],
      dept_code: ['SAL'],
      expiry_date: [''],
      batch_no: [''],
      doc_type: [''],
      comp_code: [''],
      item_name: [''],
      markup: [''],
      markdown: [''],
      created_by: ['Admin'],
      status: ['Active'],
      created_at: [''],
      _id: []
    });
  }

  ngOnInit(): void {
    this.dtOptions = {
      pagingType: 'full_numbers',
      pageLength: 10,
      processing: true,
      lengthMenu: [10,20,30],
      order:[[1,'desc']],
      destroy: true
    };
    this.getCompanyList();
    this.getDepartmentList();
  }

  getDepartmentList(){
    this.common.codeList('DEPT_TYPE', 'common-master').subscribe((res:any) => {
      this.departmentData = res.data;
    })
  }

  getItemList(event:any){
    if(event.target.value)
    this.common.barcode_data(event.target.value, 'purchase-entry').subscribe((res:any) => {
      this.branchForm.patchValue({
        item_code: res.data.item_code,
        item_name: res.data.item_name,
        item_type: res.data.item_type,
        markup: res.data.markup,
        markdown: res.data.markdown
      });
    });
  }

  get f(): { [key: string]: AbstractControl } {
    return this.branchForm.controls;
  }

  getCompanyList(){
    this.submitBtn == 'SAVE';
    this.common.list(this.view).subscribe((res:any) => {
      this.data = res.lists;
      this.dtTrigger.next(null);
    })
  }

  onMRP(event: any){
    const mrp = event.target.value;
    const markdown = this.branchForm.controls['markdown'].value;
    this.branchForm.patchValue({
      sale_rate: mrp - (mrp * markdown) / 100
    });
  }

  onSaleRate(event: any){
    const salerate = event.target.value;
    const markup = this.branchForm.controls['markup'].value;
    this.branchForm.patchValue({
      cost_rate: salerate - (salerate * markup) / 100
    });
  }

  onSubmit(): void {
    this.branchForm.value['created_by'] = 'Admin';
    this.submitted = true;
    if (this.branchForm.invalid) {
      return;
    }else{
      if(this.submitBtn == 'SAVE'){
        this.common.save(this.branchForm.value, this.view).subscribe((res:any) => {
          this.getCompanyList();
          window.location.reload();
        }, (err:any) => {
          if (err.status == 400) {
            const validationError = err.error.errors;
            Object.keys(validationError).forEach((index) => {
              const formControl = this.branchForm.get(
                validationError[index].param
              );
              if (formControl) {
                formControl.setErrors({
                  serverError: validationError[index].msg,
                });
              }
            });
          }
        })
      }else if(this.submitBtn == 'UPDATE'){
        this.common.update(this.branchForm.value, this.view).subscribe((res:any) => {
          this.getCompanyList();
        })
      }
      this.onReset();
    }
  }

  onReset(): void {
    this.submitted = false;
    this.branchForm.reset();
  }

  editCompanyList(id: any){
    this.submitBtn = 'UPDATE'
    this.common.edit(id, this.view).subscribe((res:any) => {
      this.branchForm.patchValue({
        loc_code: res.data[0].loc_code,
        barcode: res.data[0].barcode,
        item_code: res.data[0].item_code,
        qty: res.data[0].qty,
        mrp: res.data[0].mrp,
        sale_rate: res.data[0].sale_rate,
        cost_rate: res.data[0].cost_rate,
        dept_code: res.data[0].dept_code,
        expiry_date: res.data[0].expiry_date,
        batch_no: res.data[0].batch_no,
        doc_type: res.data[0].doc_type,
        item_name: res.data[0].item_name,
        markup: res.data[0].markup,
        markdown: res.data[0].markdown,
        status: res.data[0].status,
        created_by: res.data[0].created_by,
        created_at: res.data[0].created_at,
        _id: res.data[0]._id
      });
    })
  }

  deleteCompanyList(id:any){
    this.common.delete(id, this.view).subscribe((res:any) => {
      this.getCompanyList();
    })
  }

  generatePDF() {  
    let docDefinition = {  
      content: [
        {
          text: 'TEST Company',
          style: 'header'
        },	
        {
          text: 'Paramater Master Report',
          style: ['subheader']
        },
        {
          style: 'tableExample',
          table: {
            widths: ['*', '*', '*', '*', '*'],
            body: [
              ['Code', 'Value', 'Description', 'Data Type', 'Created By']
            ].concat(this.data.map((el:any, i:any) => [el.data.list_code, el.data.list_value, el.data.list_desc, el.data.data_type, el.data.created_by]))
          }
        },
      ],
      styles: {
        header: {
          fontSize: 18,
          bold: true
        },
        subheader: {
          fontSize: 15,
          bold: true
        },
        quote: {
          italics: true
        },
        small: {
          fontSize: 8
        }
      }
    };  
   
    pdfMake.createPdf(docDefinition).print();  
  } 

  generateExcel(): void
  {
    let element = document.getElementById('table');
    const ws: XLSX.WorkSheet =XLSX.utils.table_to_sheet(element);
    const wb: XLSX.WorkBook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, 'Sheet1');
    XLSX.writeFile(wb, 'Download.xls');
  }

  public downloadAsPDF() {
    const doc = new jsPDF();
    const pdfTable = this.pdfTable.nativeElement;
    var html = htmlToPdfmake(pdfTable.innerHTML);
    var documentDefinition = { 
      content: [html],
      styles: {
        
      }
    };
    pdfMake.createPdf(documentDefinition).open();
  }
} 

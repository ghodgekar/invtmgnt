import { Component, ElementRef, OnInit, ViewChild } from '@angular/core';
import { AbstractControl, FormBuilder, FormGroup, Validators } from '@angular/forms';

import jsPDF from 'jspdf';
import * as pdfMake from 'pdfmake/build/pdfmake.js';
import * as pdfFonts from 'pdfmake/build/vfs_fonts.js';
(pdfMake as any).vfs = pdfFonts.pdfMake.vfs;

import * as XLSX from 'xlsx';
import htmlToPdfmake from 'html-to-pdfmake';
import { CommonListService } from 'src/app/services/master/common-list.service';
import { BranchService } from 'src/app/services/master/branch.service';
import { ToastrMsgService } from 'src/app/services/components/toastr-msg.service';
import { DatePipe } from '@angular/common';
import { CommonService } from 'src/app/services/common.service';
import { Subject } from 'rxjs';

@Component({
  selector: 'app-common-list',
  templateUrl: './common-list.component.html',
  styleUrls: ['./common-list.component.css']
})
export class CommonListComponent implements OnInit {

  public view: any = 'common-master';
  public created_by: any;
  public created_at: any;
  public updated_by: any;
  public updated_at: any;
  public commonlistForn!: FormGroup;
  public submitted: boolean = false;
  public submitBtn:String ='SAVE';
  public isEdit:boolean=false;
  @ViewChild('pdfTable')
  pdfTable!: ElementRef;
  public dtOptions: DataTables.Settings = {};
  public data:any=[];
  public branchData: any=[];


  constructor(private fb: FormBuilder, private common: CommonService,public datepipe: DatePipe, private toastr: ToastrMsgService) {
    this.createForm();
  }
  
  createForm() {
    this.commonlistForn = this.fb.group({
      list_code: ['', Validators.required],
      list_value: ['', Validators.required ],
      list_desc: ['', Validators.required ],
      order_by: [''],
      loc_code: ['0', Validators.required ],
      created_by: [''],
      created_at: [''],
      updated_by: [''],
      updated_at: [''],
      status: ['Active'],
      _id: []
    });
  }

  ngOnInit(): void {
    this.getCommonListDatatable();
    this.getLocationList();
  }

  get f(){
    return this.commonlistForn.controls;
  }

  getLocationList(){
    this.common.list('branch').subscribe((res:any) => {
      this.branchData = res.data;
    });
  }

  getCommonListDatatable(){
    var formData = {
      searchStatus: 'Active',
    };
    this.dtOptions = {
      processing: false,
      responsive: true,
      serverSide: true,
      destroy: true,
      autoWidth: false,
      info: true,
      dom: 'Rfrtlip',
      searching: false,
      lengthChange: true,
      ordering: false,
      scrollX: true,
      scrollCollapse: true,
      pageLength: 15,
      lengthMenu: [15, 30, 45, 60, 100],
      ajax: (dataTablesParameters: any, callback: (arg0: { recordsTotal: any; recordsFiltered: any; data: never[]; }) => void) => {
        Object.assign(dataTablesParameters, formData)
        this.common.datatable(dataTablesParameters, this.view).subscribe((resp:any) => {
          this.data = resp.data.data;
          callback({
            recordsTotal: resp.data.recordsTotal,
            recordsFiltered: resp.data.recordsFiltered,
            data: []
          });
        });
      }
    };
  }

  onSubmit(): void {
    this.commonlistForn.value['updated_by'] = localStorage.getItem('username');
    this.commonlistForn.value['updated_at'] = new Date();
    this.submitted = true;
    if (this.commonlistForn.invalid) {
      return;
    }else{
      if(this.submitBtn == 'SAVE'){
        this.commonlistForn.value['created_by'] = localStorage.getItem('username');
        this.commonlistForn.value['created_at'] = new Date();
        this.common.save( this.commonlistForn.value, this.view).subscribe((res:any) => {  
          this.onReset();
          this.toastr.showSuccess(res.message);
          $('#evaluator_table').DataTable().ajax.reload();
        }, (err:any) => {
          if (err.status == 400) {
            this.toastr.showError(err.error.message)
          }
        })
      }else if(this.submitBtn == 'UPDATE'){
        this.common.update( this.commonlistForn.value, this.view).subscribe((res:any) => {
          this.isEdit = false;
          this.submitBtn = 'SAVE';
          this.onReset();
          this.toastr.showSuccess(res.message)
          $('#evaluator_table').DataTable().ajax.reload();
        })
      }
    }
  }

  onReset(): void {
    this.submitted = false;
    this.commonlistForn.reset();
    this.submitBtn = 'SAVE';
    this.isEdit = false;
  }

  editCommonList(id: any){
    this.isEdit = true;
    this.submitBtn = 'UPDATE'
    this.common.edit(id, this.view).subscribe((res:any) => {
      this.commonlistForn.patchValue({
        list_code: res.data[0].list_code,
        list_value: res.data[0].list_value,
        list_desc: res.data[0].list_desc,
        order_by: res.data[0].order_by,
        loc_code: res.data[0].loc_code,
        status: res.data[0].status,
        created_by: res.data[0].created_by,
        created_at: res.data[0].created_at,
        updated_by: res.data[0].updated_by,
        updated_at: res.data[0].updated_at,
        _id: res.data[0]._id
      });
      this.created_by = res.data[0].created_by;
      this.created_at = this.datepipe.transform(res.data[0].created_at, 'dd-MM-YYYY HH:MM:SS');
      this.updated_by = res.data[0].updated_by;
      this.updated_at = this.datepipe.transform(res.data[0].updated_at, 'dd-MM-YYYY HH:MM:SS');
    })
  }

  deleteCommonList(id:any){
    this.common.delete( {'_id':id}, this.view ).subscribe((res:any) => {
      $('#evaluator_table').DataTable().ajax.reload();
    })
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
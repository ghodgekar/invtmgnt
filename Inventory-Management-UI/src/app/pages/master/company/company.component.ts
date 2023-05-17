import { Component, ElementRef, OnInit, ViewChild } from '@angular/core';
import { AbstractControl, FormBuilder, FormControl, FormGroup, Validators } from '@angular/forms';

import jsPDF from 'jspdf';
import * as pdfMake from 'pdfmake/build/pdfmake.js';
import * as pdfFonts from 'pdfmake/build/vfs_fonts.js';
(pdfMake as any).vfs = pdfFonts.pdfMake.vfs;

import * as XLSX from 'xlsx';
import htmlToPdfmake from 'html-to-pdfmake';
import { CommonService } from 'src/app/services/common.service';
import { ReplaySubject, Subject, takeUntil } from 'rxjs';
import { DatePipe } from '@angular/common';
import { ToastrMsgService } from 'src/app/services/components/toastr-msg.service';
import { CityService } from 'src/app/services/master/city.service';
import { MatSelect } from '@angular/material/select';

@Component({
  selector: 'app-company',
  templateUrl: './company.component.html',
  styleUrls: ['./company.component.css']
})
export class CompanyComponent implements OnInit{
  view: any = 'company';
  created_by: any;
  created_at: any;
  updated_by: any;
  updated_at: any;
  companyForm!: FormGroup;
  submitted: boolean = false;
  submitBtn:String ='SAVE';
  isEdit:boolean=false;
  @ViewChild('pdfTable')
  pdfTable!: ElementRef;
  dtOptions: DataTables.Settings = {};
  data:any=[];
  parent_menu: any=[];
  typeData: any;
  city_data: any=[];
  
  cityFilterCtrl: FormControl<string> = new FormControl<any>('');
  @ViewChild('singleSelect', { static: true })singleSelect!: MatSelect;
  city_data_arr: ReplaySubject<any> = new ReplaySubject<any>(1);
  _onDestroy = new Subject<void>();

  constructor(private fb: FormBuilder, private common: CommonService, public datepipe: DatePipe, private toastr: ToastrMsgService) {
    this.createForm();
  }
  
  createForm() {
    this.companyForm = this.fb.group({
      comp_code: ['', Validators.required],
      comp_name: ['', Validators.required ],
      type: ['', Validators.required ],
      addr1: ['', Validators.required ],
      addr2: [''],
      addr3: ['' ],
      city: ['', Validators.required ],
      state: ['', Validators.required ],
      country: ['', Validators.required ],
      pincode: ['', Validators.required ],
      std_code: [''],
      phone: [''],
      mobile: [''],
      gstin: [''],
      fassa_no: [''],
      cin_no: [''],
      pan_no: [''],
      tan_no: [''],
      lsttinpin_no: [''],
      cst_no: [''],
      coregn_no: [''],
      coregndate: [''],
      druglic_no: [''],
      importexport: [''],
      company_image: [''],
      created_by: [''],
      created_at: [''],
      updated_by: [''],
      updated_at: [''],
      status: ['Active'],
      _id: []
    });
  }

  ngOnInit(): void {
    this.getCompanyListDatatable();
    this.getTypeList();
    this.getCityList();
  }

  ngOnDestroy() {
    this._onDestroy.next();
    this._onDestroy.complete();
  }

  filterCity() {
    if (!this.city_data) {
      return;
    }
    let search = this.cityFilterCtrl.value;
    if (!search) {
      this.city_data_arr.next(this.city_data.slice());
      return;
    } else {
      search = search.toLowerCase();
    }
    this.city_data_arr.next(
      this.city_data.filter((data:any) => data.city_name.toLowerCase().indexOf(search) > -1
      )
    );
  }

  getCityList(){
    this.submitBtn == 'SAVE';
    this.common.list('city').subscribe((res:any) => {
      this.city_data = res.lists;
      this.city_data_arr.next(this.city_data.slice());
      this.cityFilterCtrl.valueChanges
        .pipe(takeUntil(this._onDestroy))
        .subscribe(() => {
          this.filterCity();
        });
    })
  }

  getTypeList(){
    this.common.codeList('COMP_TYPE', 'common-master').subscribe((res:any) => {
      this.typeData = res.data;
    })
  }

  onChanheCity(e:any){
    this.common.getStateCountry(e.value).subscribe((res:any) => {
      this.companyForm.patchValue({
        state: res.data.state_name,
        country: res.data.country_name
      });
    })
  }

  get f(): { [key: string]: AbstractControl } {
    return this.companyForm.controls;
  }
  

  getCompanyListDatatable(){
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
      scrollCollapse: false,
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
    this.companyForm.value['updated_by'] = localStorage.getItem('username');
    this.companyForm.value['updated_at'] = new Date();
    this.submitted = true;
    if (this.companyForm.invalid) {
      return;
    }else{
      if(this.submitBtn == 'SAVE'){
        this.companyForm.value['created_by'] = localStorage.getItem('username');
        this.companyForm.value['created_at'] = new Date();
        this.common.save( this.companyForm.value, this.view).subscribe((res:any) => {  
          this.onReset();
          this.toastr.showSuccess(res.message);
          $('#evaluator_table').DataTable().ajax.reload();
        }, (err:any) => {
          if (err.status == 400) {
            const validationError = err.error.errors;
            Object.keys(validationError).forEach((index) => {
              const formControl = this.companyForm.get(
                validationError[index].param
              );
              if (formControl) {
                formControl.setErrors({
                  serverError: validationError[index].msg,
                });
              }
            });
          }
          this.toastr.showError(err.error.message)
        })
      }else if(this.submitBtn == 'UPDATE'){
        this.common.update( this.companyForm.value, this.view).subscribe((res:any) => {
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
    this.companyForm.reset();
    this.submitBtn = 'SAVE';
    this.isEdit = false;
  }

  editCompanyList(id: any){
    this.submitBtn = 'UPDATE'
    this.common.edit(id, this.view).subscribe((res:any) => {
      this.companyForm.patchValue({
        comp_code: res.data[0].comp_code,
        comp_name: res.data[0].comp_name,
        type: res.data[0].type,
        addr1: res.data[0].addr1,
        addr2: res.data[0].addr2,
        addr3: res.data[0].addr3,
        city: res.data[0].city,
        state: res.data[0].state,
        country: res.data[0].country,
        std_code: res.data[0].std_code,
        pincode: res.data[0].pincode,
        phone: res.data[0].phone,
        mobile: res.data[0].mobile,
        gstin: res.data[0].gstin,
        fassa_no: res.data[0].fassa_no,
        cin_no: res.data[0].cin_no,
        pan_no: res.data[0].pan_no,
        tan_no: res.data[0].tan_no,
        lsttinpin_no: res.data[0].lsttinpin_no,
        cst_no: res.data[0].cst_no,
        coregn_no: res.data[0].coregn_no,
        coregndate: res.data[0].coregndate,
        druglic_no: res.data[0].druglic_no,
        importexport: res.data[0].importexport,
        created_by: res.data[0].created_by,
        created_at: res.data[0].created_at,
        updated_by: res.data[0].updated_by,
        updated_at: res.data[0].updated_at,
        status: res.data[0].status,
        _id: res.data[0]._id
      });
      this.created_by = res.data[0].created_by;
      this.created_at = this.datepipe.transform(res.data[0].created_at, 'dd-MM-YYYY HH:MM:SS');
      this.updated_by = res.data[0].updated_by;
      this.updated_at = this.datepipe.transform(res.data[0].updated_at, 'dd-MM-YYYY HH:MM:SS');
    })
    this.isEdit = true;
  }

  deleteCompanyList(id:any){
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

  downloadAsPDF() {
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
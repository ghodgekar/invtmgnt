import { Component, ElementRef, OnInit, ViewChild } from '@angular/core';
import { AbstractControl, FormArray, FormBuilder, FormGroup, Validators } from '@angular/forms';

import jsPDF from 'jspdf';
import * as pdfMake from 'pdfmake/build/pdfmake.js';
import * as pdfFonts from 'pdfmake/build/vfs_fonts.js';
(pdfMake as any).vfs = pdfFonts.pdfMake.vfs;

import * as XLSX from 'xlsx';
import htmlToPdfmake from 'html-to-pdfmake';
import { Subject } from 'rxjs';
import { OpningStockService } from 'src/app/services/transactions/opning-stock.service';
import { DatePipe } from '@angular/common';
import { CommonService } from 'src/app/services/common.service';
@Component({
  selector: 'app-stock-adjustment',
  templateUrl: './stock-adjustment.component.html',
  styleUrls: ['./stock-adjustment.component.css']
})
export class StockAdjustmentComponent {

    public view: any = 'stock-adjustment';
    rateForm!: FormGroup;
    submitted: boolean = false;
    data:any=[];
    parent_menu: any=[];
    submitBtn:String ='SAVE';
  
    @ViewChild('pdfTable')
    pdfTable!: ElementRef;
  
    dtOptions: DataTables.Settings = {};
    dtTrigger: Subject<any> = new Subject();
    location!: any;
    public reasonList: any;
    public batchList: any;
    public lotList: any;
    public isSelect: boolean = true;
  
    constructor(
      private fb: FormBuilder, 
      private OpeningStockHttp: OpningStockService,
      private datePipe: DatePipe,
      private common: CommonService
    ) {
      this.createForm();
    }
    
    createForm() {
      this.rateForm = this.fb.group({
        v_date: [this.datePipe.transform(new Date(), 'yyyy-MM-dd'), Validators.required ],
        narration: [''],
        dept_name: ['SAL'],
        adjmnt_type: ['M'],
        auth_flag: [''],
        comp_code: [''],
        sachi: [''],
        u_date: [''],
        auth_ucode_sachi: [''],
        auth_ucode_date: [''],
        created_by: ['Admin'],
        created_at: [''],
        _id: [],
        barcodes: this.fb.array([]), 
      });
    }
  
    ngOnInit(): void {
      this.location = localStorage.getItem('location');
      this.addBarcode();
      this.common.branchState('branch', this.location).subscribe(
        (res: any) => {
          this.rateForm.patchValue({
            comp_code: res.data[0].comp_code
          });
        });
      this.getList();
    }

    get barcodes() : FormArray {  
      return this.rateForm.get("barcodes") as FormArray
    }

    newBarcode(): FormGroup {  
      return this.fb.group({  
        sku_code: '',
        sku_name: '',
        reason: '',
        rec_iss: '',
        qty: '',
        lot_no: '',
        bal_qty: '',
        sale_rate: '',
        mrp: '',
        cost_rate: '',
        expiry_date: '',
      });
    }

    addBarcode() {  
      this.barcodes.push(this.newBarcode());  
    }

    removeBarcode(i:number) {  
      this.barcodes.removeAt(i);  
    }
  
    get f(): { [key: string]: AbstractControl } {
      return this.rateForm.controls;
    }

    getItemList(event:any, i:any){
      if(event.target.value)
      this.common.barcode_data(event.target.value, 'purchase-entry').subscribe((res:any) => {
        this.barcodes.at(i).get('sku_name')?.setValue(res.data.item_name);
        this.barcodes.at(i).get('bal_qty')?.setValue(res.data.qty_in_case);
        this.batchList = res.data.openingstock;
      });
    }

    getList(){
      this.common.list('reason').subscribe(
        (data: any) => {
          this.reasonList = data.lists;
        }
      );
    }

    onReasonSelect(event: any, i: any){
      this.common.getByCode(event.target.value, 'reason').subscribe(
        (res: any) => {
          this.barcodes.at(i).get('rec_iss')?.setValue(res.data[0].rec_iss);
          if(res.data[0].new_adj == 'N'){
            this.isSelect = false;
          }else{
            this.isSelect = true;
          }
        }
      );
    }

    lotchange(event: any, i:any){
      this.common.lotnoChange('opening-stock/lot-no', event.target.value, this.barcodes.at(i).get('sku_code')?.value).subscribe(
        (res: any) => {
          this.barcodes.at(i).get('sale_rate')?.setValue(res.lot.sale_rate);
          this.barcodes.at(i).get('cost_rate')?.setValue(res.lot.cost_rate);
          this.barcodes.at(i).get('mrp')?.setValue(res.lot.mrp);
        }
      )
    }
  
    onSubmit(): void {
      this.rateForm.value['created_by'] = 'Admin';
      this.submitted = true;
      if (this.rateForm.invalid) {
        return;
      }else{
        if(this.submitBtn == 'SAVE'){
          this.common.save(this.rateForm.value, this.view).subscribe((res:any) => {
            // this.getCompanyList();
            window.location.reload();
          }, (err:any) => {
            if (err.status == 400) {
              const validationError = err.error.errors;
              Object.keys(validationError).forEach((index) => {
                const formControl = this.rateForm.get(
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
          this.common.update(this.rateForm.value, this.view).subscribe((res:any) => {
            // this.getCompanyList();
          })
        }
        this.onReset();
      }
    }
  
    onReset(): void {
      this.submitted = false;
      this.rateForm.reset();
    }

    generateExcel() {  
    } 
  
    downloadAsPDF(): void
    {
    }

  } 


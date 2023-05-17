import { Component, ElementRef, OnInit, ViewChild } from '@angular/core';
import { AbstractControl, FormArray, FormBuilder, FormGroup, Validators } from '@angular/forms';

import jsPDF from 'jspdf';
import * as pdfMake from 'pdfmake/build/pdfmake.js';
import * as pdfFonts from 'pdfmake/build/vfs_fonts.js';
(pdfMake as any).vfs = pdfFonts.pdfMake.vfs;

import * as XLSX from 'xlsx';
import htmlToPdfmake from 'html-to-pdfmake';
import { Subject, Subscription } from 'rxjs';
import { OpningStockService } from 'src/app/services/transactions/opning-stock.service';
import { DatePipe } from '@angular/common';
import { CommonService } from 'src/app/services/common.service';
import { ToastrMsgService } from 'src/app/services/components/toastr-msg.service';

@Component({
  selector: 'app-purchase-entry',
  templateUrl: './purchase-entry.component.html',
  styleUrls: ['./purchase-entry.component.css']
})
export class PurchaseEntryComponent {

  public view: any = 'purchase-entry';
  purchaseEntryForm!: FormGroup;
  submitted: boolean = false;
  data:any=[];
  parent_menu: any=[];
  submitBtn:String ='SAVE';

  @ViewChild('pdfTable')
  pdfTable!: ElementRef;
  public supplierList: any;
  public taxList: any;
  public brokerList: any;
  public taxData: any;
  public markvalue: any;
  public location: any;
  public branchstate: any;
  public supplierstate: any = '';
  public taxtype: any;

  public cgst: number = 0;
  public sgst: number = 0;
  public igst: number = 0;
  public utgst: number = 0;
  public cess: number = 0;
  public cessperpiece: number = 0;
  public hsncode: number = 0;

  public cgst1: number = 0;
  public sgst1: number = 0;
  public igst1: number = 0;
  public utgst1: number = 0;
  public cess1: number = 0;
  public cessperpiece1: number = 0;

  public totalRows: any = 1;
  public totalBillQty: any = 0;
  public totalFreeQty: any = 0;
  public totalQtyCol: any = 0;
  public totalTaxableAmt: any = 0;
  public totalItemDisc: any = 0;
  public totalCashDisc: any = 0;
  public totalTaxAmt: any = 0;
  public totalPurAmt: any = 0;

  dtOptions: DataTables.Settings = {};
  dtTrigger: Subject<any> = new Subject();

  constructor(
    private fb: FormBuilder, 
    private OpeningStockHttp:OpningStockService,
    private datepipe: DatePipe,
    private common: CommonService,
    private toaster: ToastrMsgService
    ) {
    this.createForm();
  }

  public invoicetypes = [
    {id: 'T', value: 'Tax'},
    {id: 'R', value: 'Retail'}
  ];
  
  createForm() {
    this.purchaseEntryForm = this.fb.group({
      loc_code: ['', Validators.required],
      v_date:[this.datepipe.transform((new Date), 'dd/MM/yyyy')],
      gnr_no: ['', Validators.required ],
      last_grn_no: ['', Validators.required ],
      po_no: [''],
      po_date: [this.datepipe.transform((new Date), 'dd-MM-yyyy')],
      gi_no: ['-'],
      invoice_type: ['T'],
      bill_no: [''],
      bill_date: [''],
      eway_billno: [''],
      eway_billdate: [''],
      supplier: [''],
      gstin: [''],
      broker: [''],
      narration: [''],
      taxable_amt: ['0'],
      item_desc: [''],
      extra_desc: [''],
      apmc: [''],
      r_off: [''],
      pur_amt: [''],
      supp_bill_amt: ['0'],
      tax_amt: ['0'],
      cash_disc: [''],
      extra_charge: [''],
      other_charge: [''],
      pur_return: [''],
      net_purchase: [''],
      cr_like_vno: [''],
      trf_br: [''],
      currbalqty: [''],
      markup: [''],
      markdown: [''],
      margin: [''],
      hsncode: [''],
      igst: [''],
      cgst: [''],
      sgst: [''],
      utgst: [''],
      cess: [''],
      cessperpiece: [''],
      batch_no: [''],
      lock_flag: [''],
      created_by: ['Admin'],
      status: ['Active'],
      auth_by: ['Admin'],
      created_at: [this.datepipe.transform((new Date), 'dd/MM/yy')],
      auth_date: [this.datepipe.transform((new Date), 'dd/MM/yy')],
      _id: [],
      barcodes: this.fb.array([]),  
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
    this.location = localStorage.getItem('location');
    this.purchaseEntryForm.patchValue({
      loc_code: this.location
    });
    this.common.branchState('branch', this.location).subscribe(
      (res: any) => {
        this.branchstate = res.data[0].state;
        this.taxtype = res.data[0].stateData.state_type
      });
    this.getSupplierList();
    this.getCompanyList();
    this.getTax();
    for (let index = 0; index < 1; index++) {
      this.addBarcode(); 
    }
  }

  get barcodes() : FormArray {  
    return this.purchaseEntryForm.get("barcodes") as FormArray  
  }  
     
  newBarcode(): FormGroup {  
    return this.fb.group({  
      barcode: '',
      item_name: '',
      item_code: '',
      pk_case: '1',
      qty_per_case: '',
      bill_qty: '',
      free_qty: '0',
      total_qty: '0',
      mrp: '0',
      bill_rate: '0',
      taxable_amount: '0',
      disc_percent: '0',
      item_disc: '0',
      cash_disc: '0',
      tax_cd: '',
      tax_amt: '0',
      landing_cost: '0',
      sale_rate: '0',
      purchase_amt: '0',
      expiry_date: '',
      mfg_date: '',
      narration: '',
    })  
  }
     
  addBarcode() {  
    this.barcodes.push(this.newBarcode());  
  }
     
  removeBarcode(i:number) {  
    this.barcodes.removeAt(i);  
  }

  supplierChange(event: any){
    this.common.getByCode(event.target.value, 'vendor').subscribe(
      (res: any) => {
        this.purchaseEntryForm.patchValue({
          gstin: res.data[0].gstin
        });
        this.supplierstate = res.data[0].state;
      }
    );
  }

  getItemList(event:any, i:any){
    if(this.supplierstate == ''){
      this.toaster.showError('Please select supplier');
      window.scrollTo(0, 0);
      event.target.value = '';
    }else{
      if(event.target.value)
      this.common.barcode_data(event.target.value, 'purchase-entry').subscribe((res:any) => {
        // console.log(res);
        this.cgst = res.data.tax.cgst;
        this.sgst = res.data.tax.sgst;
        this.igst = res.data.tax.igst;
        this.utgst = res.data.tax.utgst;
        this.cess = res.data.tax.cess;
        this.cessperpiece = res.data.tax.cessperpiece;
        this.hsncode = res.data.hsn;
        this.purchaseEntryForm.patchValue({
          markup: res.data.markup,
          markdown: res.data.markdown,
          currbalqty: res.data.qty_in_case,
        });
        this.barcodes.at(i).get('item_name')?.setValue(res.data.item_name);
        this.barcodes.at(i).get('item_code')?.setValue(res.data.item_code);
        this.barcodes.at(i).get('qty_per_case')?.setValue(res.data.qty_in_case);
        this.barcodes.at(i).get('bill_qty')?.setValue(this.barcodes.at(i).get('pk_case')?.value * this.barcodes.at(i).get('qty_per_case')?.value);
        if(this.taxData == 'N'){
          this.barcodes.at(i).get('tax_cd')?.setValue(res.data.tax.tax_per);
        }
        this.barcodes.at(i).get('total_qty')?.setValue(parseInt(this.barcodes.at(i).get('bill_qty')?.value) + parseInt(this.barcodes.at(i).get('free_qty')?.value));
        if(res.data.on_mrp == 'Yes' || res.data.on_mrp == 'Y'){
          const salerate = this.barcodes.at(i).get('mrp')?.value - ((this.barcodes.at(i).get('mrp')?.value * res.data.markdown)/100);
          this.barcodes.at(i).get('sale_rate')?.setValue(salerate);
          this.markvalue = res.data.markdown;
        }else{
          const salerate = this.barcodes.at(i).get('mrp')?.value - ((this.barcodes.at(i).get('mrp')?.value * res.data.markup)/100);
          this.barcodes.at(i).get('sale_rate')?.setValue(salerate);
          this.markvalue = res.data.markup;
        }
        // this.totalRows = this.barcodes.controls.length;
        // this.totalBillQty += parseInt(this.barcodes.at(i).get('bill_qty')?.value);
        // this.totalFreeQty += parseInt(this.barcodes.at(i).get('free_qty')?.value);
        // this.totalQtyCol += parseInt(this.barcodes.at(i).get('total_qty')?.value);
      }); 
    }
  }

  calculateSummary(){
    this.totalRows = 0;
    this.totalBillQty = 0;
    this.totalFreeQty = 0;
    this.totalQtyCol = 0;
    this.totalTaxableAmt = 0;
    this.totalItemDisc = 0;
    this.totalCashDisc = 0;
    this.totalTaxAmt = 0;
    this.totalPurAmt = 0;
    this.totalRows = this.barcodes.controls.length;
    this.barcodes.value.forEach((element: any) => {
      this.totalBillQty += parseFloat(element.bill_qty);
      this.totalFreeQty += parseFloat(element.free_qty);
      this.totalQtyCol += parseFloat(element.total_qty);
      this.totalTaxableAmt += parseFloat(element.taxable_amount);
      this.totalItemDisc += parseFloat(element.item_disc);
      this.totalCashDisc += parseFloat(element.cash_disc);
      this.totalTaxAmt += parseFloat(element.tax_amt);
      this.totalPurAmt += parseFloat(element.purchase_amt);
    });
    this.purchaseEntryForm.patchValue({
      taxable_amt: parseFloat(this.totalTaxableAmt).toFixed(2),
      tax_amt: parseFloat(this.totalTaxAmt).toFixed(2),
      item_desc: parseFloat(this.totalItemDisc).toFixed(2),
      cash_disc: parseFloat(this.totalCashDisc).toFixed(2),
      pur_amt: parseFloat(this.totalTaxableAmt + this.totalTaxAmt + this.totalItemDisc + this.totalCashDisc).toFixed(2),
    });
  }

  get billQtyValues() {
    return this.barcodes.get('bill_qty') as FormArray;
  }

  totalQty(event: any, i: any){
    this.barcodes.at(i).get('total_qty')?.setValue(parseInt(this.barcodes.at(i).get('bill_qty')?.value) + parseInt(this.barcodes.at(i).get('free_qty')?.value));
  }

  taxableAmt(event: any, i: any){
    this.barcodes.at(i).get('taxable_amount')?.setValue(parseFloat(this.barcodes.at(i).get('bill_qty')?.value) * parseFloat(this.barcodes.at(i).get('bill_rate')?.value));
  }

  taxableAmtChange(event: any, i: any){
    const landcost = (this.barcodes.at(i).get('taxable_amount')?.value - this.barcodes.at(i).get('item_disc')?.value - this.barcodes.at(i).get('cash_disc')?.value + this.barcodes.at(i).get('tax_amt')?.value) / this.barcodes.at(i).get('total_qty')?.value;
    this.barcodes.at(i).get('landing_cost')?.setValue(landcost);
  }

  taxAmt(event: any, i: any){
    this.barcodes.at(i).get('tax_amt')?.setValue((parseFloat(this.barcodes.at(i).get('taxable_amount')?.value) - parseFloat(this.barcodes.at(i).get('bill_rate')?.value)));
  }

  mrpChange(event: any, i: any){
    const salerate = event.target.value - ((event.target.value * this.markvalue)/100);
    this.barcodes.at(i).get('sale_rate')?.setValue(salerate);
  }

  itemDiscChange(event: any, i: any){
    this.barcodes.at(i).get('item_disc')?.setValue((this.barcodes.at(i).get('taxable_amount')?.value * this.barcodes.at(i).get('disc_percent')?.value)/100);

    this.barcodes.at(i).get('tax_amt')?.setValue(((this.barcodes.at(i).get('taxable_amount')?.value - this.barcodes.at(i).get('item_disc')?.value - this.barcodes.at(i).get('cash_disc')?.value) * this.barcodes.at(i).get('tax_cd')?.value)/100);

    this.barcodes.at(i).get('landing_cost')?.setValue(Math.round((this.barcodes.at(i).get('taxable_amount')?.value - this.barcodes.at(i).get('item_disc')?.value - this.barcodes.at(i).get('cash_disc')?.value + this.barcodes.at(i).get('tax_amt')?.value) / this.barcodes.at(i).get('total_qty')?.value), 2);

    this.barcodes.at(i).get('purchase_amt')?.setValue(Math.round(this.barcodes.at(i).get('taxable_amount')?.value - this.barcodes.at(i).get('item_disc')?.value - this.barcodes.at(i).get('cash_disc')?.value + this.barcodes.at(i).get('tax_amt')?.value), 2);

    const taxAmount = this.barcodes.at(i).get('taxable_amount')?.value - this.barcodes.at(i).get('item_disc')?.value - this.barcodes.at(i).get('cash_disc')?.value;
    if(this.supplierstate == this.branchstate && this.taxtype == 'STATE'){
      this.cgst1 = (taxAmount * (this.cgst/100));
      this.sgst1 = (taxAmount * (this.sgst/100));
      this.igst1 = 0;
      this.utgst1 = 0;
      this.cess1 = (taxAmount * (this.cess / 100));
      this.cessperpiece1 = (taxAmount * (this.cessperpiece / 100));
    }else if(this.supplierstate == this.branchstate && this.taxtype == 'UT'){
      this.cgst1 = 0
      this.sgst1 = 0;
      this.igst1 = 0;
      this.utgst1 = (taxAmount * (this.utgst / 100));
      this.cess1 = (taxAmount * (this.cess / 100));
      this.cessperpiece1 = (taxAmount * (this.cessperpiece / 100));
    }else if(this.supplierstate != this.branchstate){
      this.cgst1 = 0
      this.sgst1 = 0;
      this.igst1 = (taxAmount * (this.igst / 100));
      this.utgst1 = 0;
      this.cess1 = (taxAmount * (this.cess / 100));
      this.cessperpiece1 = (taxAmount * (this.cessperpiece / 100));
    }
    this.purchaseEntryForm.patchValue({
      currbalqty: '0',
      margin: '0',
      hsncode: this.hsncode,
      igst: this.igst1,
      cgst: this.cgst1,
      sgst: this.sgst1,
      utgst: this.utgst1,
      cess: this.cess1,
      cessperpiece: this.cessperpiece1,
    });
  }

  cashDiscChange(event: any, i: any){
    this.barcodes.at(i).get('tax_amt')?.setValue(((this.barcodes.at(i).get('taxable_amount')?.value - this.barcodes.at(i).get('item_disc')?.value - this.barcodes.at(i).get('cash_disc')?.value) * this.barcodes.at(i).get('tax_cd')?.value)/100);

    this.barcodes.at(i).get('landing_cost')?.setValue(Math.round((this.barcodes.at(i).get('taxable_amount')?.value - this.barcodes.at(i).get('item_disc')?.value - this.barcodes.at(i).get('cash_disc')?.value + this.barcodes.at(i).get('tax_amt')?.value) / this.barcodes.at(i).get('total_qty')?.value), 2);

    this.barcodes.at(i).get('purchase_amt')?.setValue(Math.round(this.barcodes.at(i).get('taxable_amount')?.value - this.barcodes.at(i).get('item_disc')?.value - this.barcodes.at(i).get('cash_disc')?.value + this.barcodes.at(i).get('tax_amt')?.value), 2);

    const taxAmount = this.barcodes.at(i).get('taxable_amount')?.value - this.barcodes.at(i).get('item_disc')?.value - this.barcodes.at(i).get('cash_disc')?.value;
    if(this.supplierstate == this.branchstate && this.taxtype == 'STATE'){
      this.cgst1 = (taxAmount * (this.cgst/100));
      this.sgst1 = (taxAmount * (this.sgst/100));
      this.igst1 = 0;
      this.utgst1 = 0;
      this.cess1 = (taxAmount * (this.cess / 100));
      this.cessperpiece1 = (taxAmount * (this.cessperpiece / 100));
    }else if(this.supplierstate == this.branchstate && this.taxtype == 'UT'){
      this.cgst1 = 0
      this.sgst1 = 0;
      this.igst1 = 0;
      this.utgst1 = (taxAmount * (this.utgst / 100));
      this.cess1 = (taxAmount * (this.cess / 100));
      this.cessperpiece1 = (taxAmount * (this.cessperpiece / 100));
    }else if(this.supplierstate != this.branchstate){
      this.cgst1 = 0
      this.sgst1 = 0;
      this.igst1 = (taxAmount * (this.igst / 100));
      this.utgst1 = 0;
      this.cess1 = (taxAmount * (this.cess / 100));
      this.cessperpiece1 = (taxAmount * (this.cessperpiece / 100));
    }
    this.purchaseEntryForm.patchValue({
      currbalqty: '0',
      margin: '0',
      hsncode: this.hsncode,
      igst: this.igst1,
      cgst: this.cgst1,
      sgst: this.sgst1,
      utgst: this.utgst1,
      cess: this.cess1,
      cessperpiece: this.cessperpiece1,
    });
  }

  taxAmtChange(event: any, i: any){
    this.barcodes.at(i).get('landing_cost')?.setValue(Math.round(((this.barcodes.at(i).get('taxable_amount')?.value - this.barcodes.at(i).get('item_disc')?.value - this.barcodes.at(i).get('cash_disc')?.value + this.barcodes.at(i).get('tax_amt')?.value)) / this.barcodes.at(i).get('total_qty')?.value), 2);

    this.barcodes.at(i).get('purchase_amt')?.setValue(Math.round(this.barcodes.at(i).get('taxable_amount')?.value - this.barcodes.at(i).get('item_disc')?.value - this.barcodes.at(i).get('cash_disc')?.value + this.barcodes.at(i).get('tax_amt')?.value), 2);
  }

  purchaseAmtChange(event: any, i: any){
    // this.totalPurAmt += parseFloat(event.target.value);
  }

  pkCase(event:any, i:any){
    const pkcase = this.barcodes.at(i).get('pk_case')?.value;
    const qtypk = this.barcodes.at(i).get('qty_per_case')?.value;
    this.barcodes.at(i).get('bill_qty')?.setValue(qtypk * pkcase);
    this.barcodes.at(i).get('total_qty')?.setValue(parseInt(this.barcodes.at(i).get('bill_qty')?.value) + parseInt(this.barcodes.at(i).get('free_qty')?.value));
  }

  get f(): { [key: string]: AbstractControl } {
    return this.purchaseEntryForm.controls;
  }

  getCompanyList(){
    this.submitBtn == 'SAVE';
    this.common.list(this.view).subscribe((res:any) => {
      // this.data = res.lists;
      this.dtTrigger.next(null);
    });
  }

  getSupplierList(){
    this.common.list('vendor').subscribe((res:any) => {
      this.supplierList = res.lists;
    });
    this.common.list('tax').subscribe((res:any) => {
      this.taxList = res.lists;
    });

    this.common.list('broker').subscribe((res:any) => {
      this.brokerList = res.lists;
    });
  }

  getTax(){
    this.common.codeList('UPD_TAX_IN_GRN', 'parameter').subscribe(
      (res: any) => {
        this.taxData = res.data[0].param_value;
      }
    )
  }

  onSubmit(): void {
    this.purchaseEntryForm.value['created_by'] = 'Admin';
    this.submitted = true;
    if (this.purchaseEntryForm.invalid) {
      return;
    }else{
      if(this.submitBtn == 'SAVE'){
        this.common.save(this.purchaseEntryForm.value, this.view).subscribe((res:any) => {
          this.getCompanyList();
          this.purchaseEntryForm.patchValue({
            batch_no: res.data.batch_no
          });
        }, (err:any) => {
          if (err.status == 400) {
            const validationError = err.error.errors;
            Object.keys(validationError).forEach((index) => {
              const formControl = this.purchaseEntryForm.get(
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
        this.common.update(this.purchaseEntryForm.value, this.view).subscribe((res:any) => {
          this.getCompanyList();
        })
      }
      // this.onReset();
    }
  }

  onReset(): void {
    this.submitted = false;
    this.purchaseEntryForm.reset();
  }

  editCompanyList(id: any){
    this.submitBtn = 'UPDATE'
    this.common.edit(id, this.view).subscribe((res:any) => {
      this.purchaseEntryForm.patchValue({
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
        gstin: res.data[0].gstin,
        comp_code: res.data[0].comp_code,
        bank_ac_no: res.data[0].bank_ac_no,
        image: res.data[0].image,
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

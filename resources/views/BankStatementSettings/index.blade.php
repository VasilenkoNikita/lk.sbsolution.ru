{{ csrf_field() }}
<div id="bankStatements">
    <table-bank-statements :bankStatements="{{ $bankStatements }}" :client="{{ $client }}"></table-bank-statements>
</div>

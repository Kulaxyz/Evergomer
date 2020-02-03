<div class="m-t-10 m-b-10 p-l-10 p-r-10 p-t-10 p-b-10">
    <div class="row">
        <div class="col-md-12">
            @php
                $owner = $entry->owner;
            @endphp
            <small>Device Owner Info</small><br><br>
            @if(backpack_user()->hasRole('admin'))
                <strong>Owner Link:</strong> {!!  $entry->ownerLink()  !!} <br>
            @endif
            <strong>Purchase Type:</strong> {{ $entry->purchaseType->name }} <br>
            <strong>Owner Contact Name:</strong> {{ $owner->name }} <br>
            <strong>Email:</strong> {{ $owner->email }} <br>
            <strong>Phone Number:</strong> {{ $owner->phone }} <br>
            @isset($owner->address)
                <strong>Address:</strong> {{ $owner->address }} <br>
            @endif
        </div>
    </div>
</div>
<div class="clearfix"></div>

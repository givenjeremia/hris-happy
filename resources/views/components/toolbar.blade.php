<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            @if (isset($title))
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $title ?? '' }}</h1>
                </div>
            @endif
            @if (isset($subtitle))
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                        <li class="breadcrumb-item active">{{ $subtitle }}</li>
                    </ol>
                </div>
            @endif
        </div>
    </div>
</div>

<div class="card">
    <header class="card-header">
        <p class="card-header-title">
            {{ __('Activities') }}
        </p>
        <a href="#" class="card-header-icon" aria-label="more options">
            <span class="icon">
                <x-fa name="angle-down" library="solid"/>
            </span>
        </a>
    </header>
    <div class="card-table">
        <div class="content">
            <table class="table is-fullwidth is-striped">
                <tbody>
                @foreach($activities as $activity)
                    <tr>
                        <td><x-fa name="bell" library="regular"/></td>
                        <td>{{ class_basename($activity->subject) }}
                            has been {{ $activity->getExtraProperty('action') }}
                            by {{ optional($activity->causer)->name }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <footer class="card-footer">
        <a href="#" class="card-footer-item">{{ __('View All') }}</a>
    </footer>
</div>
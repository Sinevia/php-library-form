[![Gitpod Ready-to-Code](https://img.shields.io/badge/Gitpod-Ready--to--Code-blue?logo=gitpod)](https://gitpod.io/#https://github.com/Sinevia/php-library-form) 

# Form

A form helper

## Building Forms ##

```php
$form = \Sinevia\Form::build($fields)->toHtml();
```

## Validating Forms ##

```php
$isValidOrErrors = \Sinevia\Form::validate($fields);
```

## Fields ##

The field is an associative array consisting of the following key-value pairs:

- type - one of text, textarea, select, hidden, html
- name - name of the input field as seen in the request
- label - publicly visible name
- width - width of the field - min 1, max 12
- rule - rules for the field, used when validating
- value - value of the field
- options - array of options (used by the select type)
- html - raw HTML to be displayed as-is (used by the html type)

Example:

```php
[
    'type' => 'text',
    'name' => 'FirstName',
    'label' => 'First name',
    'width' => 6,
    'rule' => 'required',
    'value' => $value,
]
```

## Full Example

```php
function formProfileFields($user) {
    $countriesList = \App\Models\Countries\Country::all()->pluck('Name', 'Iso2')->toArray();
    asort($countriesList);
    $countries = ['' => '- country -'] + $countriesList;

    $daysRange = range(1, 12);
    $days = [
        '' => '- day -'
    ];
    foreach ($daysRange as $day) {
        $days[$day] = $day;
    }

    $monthsRange = range(1, 12);
    $months = [
        '' => '- month -'
    ];
    foreach ($monthsRange as $month) {
        $months[$month] = $month;
    }

    $yearsRange = range(1940, 2014);
    $years = [
        '' => '- year -'
    ];
    foreach ($yearsRange as $year) {
        $years[$year] = $year;
    }

    $fields = [
        [
            'type' => 'html',
            'html' => '<style>.btn-success { width:100%; padding:10px;}</style>',
        ],
        [
            'type' => 'html',
            'html' => '<div class="col-sm-12"><h3>Profile</h3></div>',
        ],
        [
            'type' => 'text',
            'name' => 'FirstName',
            'label' => 'First name',
            'width' => 6,
            'rule' => 'required',
            'value' => $user->FirstName,
        ],
        [
            'type' => 'text',
            'name' => 'LastName',
            'label' => 'last name',
            'width' => 6,
            'rule' => 'required',
            'value' => $user->LastName,
        ],
        [
            'type' => 'select',
            'name' => 'DayOfBirth',
            'options' => $days,
            'label' => 'Day of Birth',
            'width' => 2,
            'rule' => 'required',
            'value' => is_null($user->Birthday) ? '' : date('d', strtotime($user->Birthday)),
        ],
        [
            'type' => 'select',
            'name' => 'MonthOfBirth',
            'options' => $months,
            'label' => 'Month of Birth',
            'width' => 2,
            'rule' => 'required',
            'value' => is_null($user->Birthday) ? '' : date('m', strtotime($user->Birthday)),
        ],
        [
            'type' => 'select',
            'name' => 'YearOfBirth',
            'options' => $years,
            'label' => 'Year of Birth',
            'width' => 2,
            'rule' => 'required',
            'value' => is_null($user->Birthday) ? '' : date('Y', strtotime($user->Birthday)),
        ],
        [
            'type' => 'html',
            'html' => '<div class="col-sm-12"><h3>Address</h3></div>',
        ],
        [
            'type' => 'select',
            'name' => 'Country',
            'label' => 'Country',
            'options' => $countries,
            'width' => 6,
            'rule' => 'required',
            'value' => $user->Country,
        ],
        [
            'type' => 'text',
            'name' => 'City',
            'label' => 'City',
            'rule' => 'required',
            'width' => 6,
            'value' => $user->City,
        ],
        [
            'type' => 'text',
            'name' => 'AddressLine1',
            'label' => 'Address Line 1',
            'rule' => 'required',
            'width' => 6,
            'value' => $user->Address1,
        ],
        [
            'type' => 'text',
            'name' => 'AddressLine2',
            'label' => 'Address Line 2 (optional)',
            'width' => 6,
            'value' => $user->Address2,
        ],
        [
            'type' => 'text',
            'name' => 'Province',
            'label' => 'Province / State / County',
            'rule' => 'required',
            'width' => 6,
            'value' => $user->Province,
        ],
        [
            'type' => 'text',
            'name' => 'Postcode',
            'label' => 'Postcode / Zip',
            'rule' => 'required',
            'width' => 6,
            'value' => $user->Postcode,
        ],
        [
            'type' => 'hidden',
            'name' => 'UserId',
            'label' => 'UserId',
            'rule' => 'required',
            'width' => 6,
            'value' => $user->Id,
        ],
    ];

    return $fields;
}

function formProfile($user) {
    return \Sinevia\Form::build($this->formProfileFields($user))->toHtml();
}

$isValidOrErrorArray = \Sinevia\Form::validate($this->formProfileFields($user));

if(is_array($isValidOrErrorArray)){
    // Validation failed, show errors
} else {
    // Validation was successful
}
```

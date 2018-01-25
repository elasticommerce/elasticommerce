if (ctx._source.filter_numeric == null || ctx._source.filter_numeric.size() == 0) {
    ctx._source.filter_numeric = params.update
} else {
    for (int i = 0;
    i < ctx._source.filter_numeric.size();
    i++
)
    {
        if (ctx._source.filter_numeric[i].name == update[0].name) {
            ctx._source.fields.remove(i);
        }
    }
}
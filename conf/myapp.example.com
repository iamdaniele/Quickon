{
	"label": "Production environment",
	"environment": "live",
	"host": "myapp.example.com",
	"database": {
		"driver": "mysql",
		"name": "myapp_database",
		"host": "127.0.0.1",
		"user": "myotheruser",
		"pass": "mY0t3R.pass"
	}
}

{
    "swagger": "2.0",
    "info": {
        "version": "0.0.0.beta",
        "title": "Trip Builder API"
    },
    "schemes": [
        "http"
    ],
    "tags": [
        {
            "name": "Airports",
            "description": "Airports operations"
        },
        {
            "name": "Trips",
            "description": "Trips operations"
        },
        {
            "name": "Flights",
            "description": "Flights operations"
        }
    ],
    "paths": {
        "/airport": {
            "get": {
            	"tags": ["Airports"],
                "summary": "Return a list of all airports in the entire world.\n",
                "parameters": [
                    {
                        "name": "code",
                        "in": "query",
                        "description": "You can search by code",
                        "required": false,
                        "type": "string",
                        "default": ""
                    }
                ],
                "responses": {
                    "200": {
                        "description": "Airport listing",
                        "schema": {
                            "title": "ArrayOfAirports",
                            "type": "array",
                            "items": {
                                "title": "Airport",
                                "type": "object",
                                "properties": {
                                    "code": {
                                        "type": "string"
                                    },
                                    "city": {
                                        "type": "string"
                                    },
                                    "country": {
                                        "type": "string"
                                    }
                                }
                            }
                        }
                    }
                }
            }
        },
        "/trip": {
            "get": {
            "tags": ["Trips"],
                "summary": "Return informations about a trip\n",
                "parameters": [
                    {
                        "name": "trip_id",
                        "in": "query",
                        "description": "Trip identifier",
                        "required": true,
                        "type": "integer",
                        "default": ""
                    }
                ],
                "responses": {
                    "200": {
                        "description": "Trip Info",
                        "schema": {
                            "title": "TripInfo",
                            "type": "object",
                            "properties": {
                                "trip_id": {
                                    "type": "integer"
                                },
                                "flights": {
                                    "type": "object",
                                    "properties": {
                                        "airport_origin": {
                                            "type": "string"
                                        },
                                        "airport_destination": {
                                            "type": "string"
                                        },
                                        "departure_date": {
                                            "type": "string"
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            },
            "post": {
            	"tags": ["Trips"],
                "summary": "Create a new trip\n",
                "parameters": [
                    {
                        "name": "customer_id",
                        "in": "formData",
                        "description": "Customer identifier",
                        "required": true,
                        "type": "integer",
                        "default": ""
                    }
                ],
                "responses": {
                    "200": {
                        "description": "Success"
                    }
                }
            }
        },
        "/flight": {
            "post": {
            	"tags": ["Flights"],
                "summary": "Add a flight to a given trip\n",
                "parameters": [
                    {
                        "name": "trip_id",
                        "in": "query",
                        "description": "Trip identifier",
                        "required": true,
                        "type": "integer",
                        "default": ""
                    },
                    {
                        "name": "airport_origin",
                        "in": "formData",
                        "description": "Origin IATA Airport code",
                        "required": true,
                        "type": "string",
                        "default": ""
                    },
                    {
                        "name": "airport_destination",
                        "in": "formData",
                        "description": "Destination IATA Airport code",
                        "required": true,
                        "type": "string",
                        "default": ""
                    },
                    {
                        "name": "departure_date",
                        "in": "formData",
                        "description": "Departure date (SQL format, in UTC)",
                        "required": false,
                        "type": "string",
                        "format": "date-time",
                        "default": ""
                    }
                ],
                "responses": {
                    "200": {
                        "description": "Success, flight added"
                    }
                }
            },
            "delete": {
            	"tags": ["Flights"],
                "summary": "Remove a flight from a trip\n",
                "parameters": [
                    {
                        "name": "trip_id",
                        "in": "query",
                        "description": "Trip identifier",
                        "required": true,
                        "type": "integer",
                        "default": ""
                    },
                    {
                        "name": "airport_origin",
                        "in": "query",
                        "description": "Origin IATA Airport code",
                        "required": true,
                        "type": "string",
                        "default": ""
                    },
                    {
                        "name": "airport_destination",
                        "in": "query",
                        "description": "Destination IATA Airport code",
                        "required": true,
                        "type": "string",
                        "default": ""
                    },
                    {
                        "name": "departure_date",
                        "in": "query",
                        "description": "Departure date (SQL format, in UTC)",
                        "required": false,
                        "type": "string",
                        "format": "date-time",
                        "default": ""
                    }
                ],
                "responses": {
                    "200": {
                        "description": "Success, flight removed"
                    }
                }
            }
        }
    }
}